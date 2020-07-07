import urllib3, urllib
#from  urllib import URLError
from urllib2  import HTTPError

import urllib.response

__all__ = ['URLError', 'HTTPError', 'ContentTooShortError']

import socket
from socket import error as SocketError
import errno
import time
from time import strftime

# from ABE_ADCPi import ADCPi
# from ABE_helpers import ABEHelpers

from pymodbus.client.sync import ModbusSerialClient as ModbusClient

startTime = time.time()
fails = 0
counter = 0
loadAvg = 0.0

# Variables
submitURL = 'http://localhost/'
failFile = 'fails.log'
inMin = 2.485
inMax = 2.7
outMin = 0.26
outMax = 3.63

intervals = (
    ('weeks', 604800),  # 60 * 60 * 24 * 7
    ('days', 86400),  # 60 * 60 * 24
    ('hours', 3600),  # 60 * 60
    ('minutes', 60),
    ('seconds', 1),
)


def display_time(seconds, granularity=4):
    result = []
    for name, count in intervals:
        value = int(seconds // count)
        if value:
            seconds -= value * count
            if value == 1:
                name = name.rstrip('s')
            result.append("{} {}".format(value, name))
    return ', '.join(result[:granularity])


def map(x, in_min, in_max, out_min, out_max):
    return (x - in_min) * (out_max - out_min) / (in_max - in_min) + out_min


def log(line):
    f = open(failFile, 'a')
    f.write(line + '\n')
    f.close()


print('Done')

# Initialise ADC
# print
# 'Initialising ADC... ',
# i2c_helper = ABEHelpers()
# bus = i2c_helper.get_smbus()
# adc = ADCPi(bus, 0x68, 0x69, 14)
# adc.set_conversion_mode(1)  # Continuous conversion
# print('Done')

# Initialise the RS845 connection
print('Initialising RS485... '),
client = ModbusClient(method='rtu', port='/dev/ttyXRUSB0', baudrate=115200)
connection = client.connect()
print('Done')

print('Starting datalogger...')

while 1:
    success = False
    processStart = time.time()

    # lI = round(map(adc.read_voltage(6), inMin, inMax, outMin, outMax), 1)  # Get the first load current reading
    # loadAvg = loadAvg + lI

    result = client.read_input_registers(0x3100, 6,unit=1)  # Request the range of registers that hold the solar/battery realtime data (3100 - 3105)
    solarVolts = float(result.registers[0] / 100.0)  # Solar voltage is register 3100, divide by 100
    solarIntensity = float(result.registers[1] / 100.0)  # Solar current is register 3101, divide by 100
    batteryVolts = float(result.registers[4] / 100.0)  # Battery voltage is register 3104, divide by 100
    batteryIntensity = float(result.registers[5] / 100.0)  # Charging current is register 3105, divide by 100

    #lI = round(map(adc.read_voltage(6), inMin, inMax, outMin, outMax), 1)  # Get a second load current reading
    #loadAvg = loadAvg + lI

    result = client.read_input_registers(0x311A, 1,unit=1)  # Request the register that holds the battery state of charge (311A)
    batteryStatus = result.registers[0] / 100  # Battery status of charge is register 311A, divide by 100

    #lI = round(map(adc.read_voltage(6), inMin, inMax, outMin, outMax), 1)  # Get a final load current reading
    #loadAvg = loadAvg + lI
    #loadAvg = loadAvg / 3  # Take a load current average

    # Ignore negative reading from current sensor
    if loadAvg < 0.0:
        loadAvg = 0.0

    print('solarVolts: ' + str(solarVolts) + ' | ' + 'solarIntensity: ' + str(solarIntensity) + ' | ' + 'batteryVolts: '
                                                                                                        '' + str(
        batteryVolts) + ' | ' + 'batteryIntensity: ' + str(
        batteryIntensity) + ' | ' + 'batteryStatus: ' + str(batteryStatus) + '%')

    # Raspberry Pi uptime
    with open('/proc/uptime', 'r') as f:
        uptime_seconds = float(f.readline().split()[0])
        systemUptime = display_time(uptime_seconds)
    
    # Program uptime
    upTime = time.time() - startTime
    upTime = display_time(upTime)

    # Submit the data with POST
    try:
        data = urllib.urlencode(
            {'time': strftime("%Y-%m-%d_%H:%M:%S"), 'solarVoltage': str(solarVolts), 'solarIntensity': str(solarIntensity), 
             'batteryVolts': str(batteryVolts), 
             'batteryIntensity': str(batteryIntensity),
             'batteryStatus': str(batteryStatus), 'uptime': upTime, 'piuptime': systemUptime})
        req = urllib2.Request(submitURL, data)
        response = urllib2.urlopen(req)
        submit = response.read()
        print(submit),
        if 'Database updated' not in submit:
            log('Failed to submit values at ' + strftime("%Y-%m-%d %H:%M:%S") + ' due to PHP/MySQL error \n' + data)
            fails += 1
        else:
            success = True
    except URLError as ue:
        print
        ue.reason,
        log('Failed to submit values at ' + strftime("%Y-%m-%d %H:%M:%S") + ' due to URL error: ' + str(
            ue.reason) + '\n' + data)
        fails += 1
    except urllib2.HTTPError as he:
        print
        he.code,
        log('Failed to submit values at ' + strftime("%Y-%m-%d %H:%M:%S") + ' due to HTTP error: ' + str(
            he.code) + '\n' + data)
        fails += 1
    except SocketError as se:
        print
        se.errno,
        log('Failed to submit values at ' + strftime("%Y-%m-%d %H:%M:%S") + ' due to SOCKET error: ' + str(
            se.errno) + '\n' + data)
        fails += 1
    except socket.timeout as te:
        print
        te,
        log('Failed to submit values at ' + strftime("%Y-%m-%d %H:%M:%S") + ' due to SOCKET TIMEOUT: ' + str(
            te) + '\n' + data)
        fails += 1

    loadAvg = 0.0

    # Show pending fails, if any
    if (fails > 0):
        print('Pending fails: ' + str(fails))

    # Check fail log every minute and retry posting them
    counter += 1
    if (counter > 7):
        counter = 0
        if (success == True and fails > 0):
            # There are pending fails but the last post was a success, retrying failed posts
            print('Checking fail log...')
            f = open('fails.log', "r+")
            d = f.readlines()
            f.seek(0)
            for i in d:
                if "si=" in i:
                    # There is a line containing pending post data
                    req = urllib2.Request(submitURL, i)
                    response = urllib2.urlopen(req)
                    submit = response.read()
                    print(submit)
                    if 'Database updated' not in submit:
                        # Post failed, keep the line
                        f.write(i)
                    else:
                        # Post successful, forget the line and decrement pending fails
                        f.write('Done \n')
                        fails -= 1
                else:
                    # There is a line with no post data (error info), leave it in
                    f.write(i)
            f.truncate()
            f.close()

    # Slow things down when inverter is off
    if (loadAvg < 0.2):
        print('')
        print('Sleeping...'),
        time.sleep(50)

    # Calculate how long this cycle took
    processFinish = time.time();
    processDuration = processFinish - processStart
    print('(' + format(processDuration, '.2f') + ' seconds)')

client.close()
