#include <linux/build-salt.h>
#include <linux/module.h>
#include <linux/vermagic.h>
#include <linux/compiler.h>

BUILD_SALT;

MODULE_INFO(vermagic, VERMAGIC_STRING);
MODULE_INFO(name, KBUILD_MODNAME);

__visible struct module __this_module
__section(.gnu.linkonce.this_module) = {
	.name = KBUILD_MODNAME,
	.init = init_module,
#ifdef CONFIG_MODULE_UNLOAD
	.exit = cleanup_module,
#endif
	.arch = MODULE_ARCH_INIT,
};

#ifdef CONFIG_RETPOLINE
MODULE_INFO(retpoline, "Y");
#endif

static const struct modversion_info ____versions[]
__used __section(__versions) = {
	{ 0xfece093d, "module_layout" },
	{ 0xadaa124, "usb_deregister" },
	{ 0x67b27ec1, "tty_std_termios" },
	{ 0xc5850110, "printk" },
	{ 0xfc2b7fa5, "put_tty_driver" },
	{ 0xd1c3e0cc, "tty_unregister_driver" },
	{ 0x4db68707, "usb_register_driver" },
	{ 0xbf9f3e9e, "tty_register_driver" },
	{ 0x3327a53c, "tty_set_operations" },
	{ 0xd45d18ee, "__tty_alloc_driver" },
	{ 0xc11b468d, "tty_port_register_device" },
	{ 0x943a2942, "usb_get_intf" },
	{ 0x29697ace, "usb_driver_claim_interface" },
	{ 0x958326fc, "_dev_info" },
	{ 0x2d6fcc06, "__kmalloc" },
	{ 0xe00ed028, "device_create_file" },
	{ 0x20ae99ec, "_dev_warn" },
	{ 0x95ab7ae2, "usb_alloc_urb" },
	{ 0x4cb6eb37, "usb_alloc_coherent" },
	{ 0x49ffd73, "tty_port_init" },
	{ 0x201a4b32, "__mutex_init" },
	{ 0x5d04aabb, "usb_ifnum_to_if" },
	{ 0x68552405, "kmalloc_caches" },
	{ 0x51a910c0, "arm_copy_to_user" },
	{ 0x5f754e5a, "memset" },
	{ 0xbc10dd97, "__put_user_4" },
	{ 0x90e58efc, "kmem_cache_alloc_trace" },
	{ 0xc6cbbc89, "capable" },
	{ 0xae353d77, "arm_copy_from_user" },
	{ 0x353e3fa5, "__get_user_4" },
	{ 0x5152e605, "memcmp" },
	{ 0x409873e3, "tty_termios_baud_rate" },
	{ 0xe707d823, "__aeabi_uidiv" },
	{ 0x3c0c04b6, "usb_autopm_put_interface" },
	{ 0x790dfb34, "usb_autopm_get_interface" },
	{ 0x8f678b07, "__stack_chk_guard" },
	{ 0xdecd0b29, "__stack_chk_fail" },
	{ 0x727a17d2, "tty_standard_install" },
	{ 0xe2b5e146, "refcount_inc_not_zero_checked" },
	{ 0x5715f95e, "tty_port_open" },
	{ 0x82bc2763, "tty_port_close" },
	{ 0xbdc204b6, "usb_autopm_get_interface_async" },
	{ 0x98915c4a, "tty_port_hangup" },
	{ 0xea0f89b6, "tty_port_tty_wakeup" },
	{ 0x37a0cba, "kfree" },
	{ 0x9b07bb6e, "usb_put_intf" },
	{ 0x86bfc366, "tty_flip_buffer_push" },
	{ 0x526d59e4, "tty_insert_flip_string_fixed_flag" },
	{ 0x2d3385d3, "system_wq" },
	{ 0xb2d48a2e, "queue_work_on" },
	{ 0x6ebe366f, "ktime_get_mono_fast_ns" },
	{ 0x3c3ff9fd, "sprintf" },
	{ 0xe1df5901, "tty_port_put" },
	{ 0x78908cf3, "usb_driver_release_interface" },
	{ 0xfd73d9df, "usb_free_urb" },
	{ 0xca87d259, "tty_unregister_device" },
	{ 0x2e0ad7fa, "tty_kref_put" },
	{ 0xf426453d, "tty_vhangup" },
	{ 0xf2298e44, "tty_port_tty_get" },
	{ 0x5fc262cb, "mutex_unlock" },
	{ 0xb8b23a09, "device_remove_file" },
	{ 0x195a71c2, "mutex_lock" },
	{ 0x29861e1a, "usb_free_coherent" },
	{ 0x4205ad24, "cancel_work_sync" },
	{ 0x24c12039, "usb_kill_urb" },
	{ 0xf1942bf4, "tty_port_tty_hangup" },
	{ 0x676bbc0f, "_set_bit" },
	{ 0x2a3aa678, "_test_and_clear_bit" },
	{ 0xd697e69a, "trace_hardirqs_on" },
	{ 0xec3d2e1b, "trace_hardirqs_off" },
	{ 0xea335ea7, "usb_autopm_put_interface_async" },
	{ 0x43cb86a2, "_dev_err" },
	{ 0x2e506df7, "usb_submit_urb" },
	{ 0x9d669763, "memcpy" },
	{ 0xdf1ee509, "usb_control_msg" },
	{ 0xb1ad28e0, "__gnu_mcount_nc" },
};

MODULE_INFO(depends, "");

MODULE_ALIAS("usb:v04E2p1410d*dc*dsc*dp*ic*isc*ip*in*");
MODULE_ALIAS("usb:v04E2p1411d*dc*dsc*dp*ic*isc*ip*in*");
MODULE_ALIAS("usb:v04E2p1412d*dc*dsc*dp*ic*isc*ip*in*");
MODULE_ALIAS("usb:v04E2p1414d*dc*dsc*dp*ic*isc*ip*in*");
MODULE_ALIAS("usb:v04E2p1420d*dc*dsc*dp*ic*isc*ip*in*");
MODULE_ALIAS("usb:v04E2p1421d*dc*dsc*dp*ic*isc*ip*in*");
MODULE_ALIAS("usb:v04E2p1422d*dc*dsc*dp*ic*isc*ip*in*");
MODULE_ALIAS("usb:v04E2p1424d*dc*dsc*dp*ic*isc*ip*in*");
MODULE_ALIAS("usb:v04E2p1400d*dc*dsc*dp*ic*isc*ip*in*");
MODULE_ALIAS("usb:v04E2p1401d*dc*dsc*dp*ic*isc*ip*in*");
MODULE_ALIAS("usb:v04E2p1402d*dc*dsc*dp*ic*isc*ip*in*");
MODULE_ALIAS("usb:v04E2p1403d*dc*dsc*dp*ic*isc*ip*in*");

MODULE_INFO(srcversion, "17922333BF9E0AEADC7EF11");
