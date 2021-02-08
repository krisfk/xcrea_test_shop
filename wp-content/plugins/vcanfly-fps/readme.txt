=== FPS 轉數快 WooCommerce Plugin - VcanFly ===
Contributors: vcanfly 
Tags: FPS, Payment
Requires at least: 5.2
Tested up to: 5.4
Stable tag: 1.0.2
Requires PHP: 5.2.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Most famous FPS check out plugin for your WooCommerce shore, with QR generation & auto reconciliation function.

== Description ==

“Faster Payment System (FPS) 轉數快” payment gateway was introduced by Hong Kong Monetary
Authority (HKMA) since Year 2018. It applies locally in HK among banks without border. This plugin is an
API with Vcan Fintech while Merchant can receive service (1) QR code transform/ (2) Payment receive/ (3)
Order status update, under a full automation system.
Customers can proceed FPS payment by scanning the QR code in webpage check-out via Bank Mobile
Application (Apps). FPS is the best choice in replacing the traditional ATM/Bank-in payment method while
this plugin helps & elevating the function in e-commerce platform.

== VcanFly FPS system advantages: ==

* Vcan provides you an account for such FPS payment transaction.
* Real-time & safe payment process without border between banks.
* Low transaction fee compares to other 3 rd party payment gateways (like Credit cards settlement)
* The QR code already embedded payment amount & merchants A/C while ease everyone by key-in amount & remarks during FPS payment.
* Obtain better cash flow than traditional 3 rd party payment gateways.
* Automated payment reconciliation, no need to upload payment receipt anymore.

== VcanFly FPS Features & Remarks: ==

VcanFly FPS plugin enable to work while you should registered & obtained a Vcan account.

* VcanFly FPS (QR Code) is a safe & reliable payment method
* FPS provide cross-bank transaction (in HK locally only)
* FPS payment (QR Code) works on Bank Mobile application (Apps) without any setting
[Mobile should connect internet & Apps should acquire function to scan QR Code]
* FPS can only work in Hong Kong which accept HKD & RMB these 2 currencies

For more information about VcanFly FPS services, please kindly contact our sales team by email: [vcanfly@vcanfintech.com](vcanfly@vcanfintech.com)

Willing to know more about FPS 轉數快?
[HKMA](https://www.hkma.gov.hk/eng/key-functions/international-financial-centre/financial-market-infrastructure/faster-payment-system-fps/)

Willing to know more about VcanFly Service?
[Vcan](https://www.vcanft.com/vcanfly-fps)

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/plugin-name` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress
1. Use the Settings->FPS Plugin screen to configure the plugin

== Frequently Asked Questions ==

Q: What do I need to use this plugin?
A: You need an Vcanfly account in order to enable this plugin. For enquiry please contact vcanfly@vcanfintech.com

== Changelog ==

= 1.0.2 =
* Fix a bug that cause an icon missing on checkout page

= 1.0.1 = 
* Fix a bug that cause the setting save not working
* Update translation

= 1.0.0 =
* Added payment gateway to enable FPS payment via vcanfly
* Added payment gateway callback
  * Change order status to `processing`
  * Store `qrCodeId` to post meta `_fps_reference_no` (defined by fps plugin)
* Added plugin setting
  * Plugins > Installed Plugins > Vcanfly FPS Plugin > Settings
  * Configurable values: _API Endpoint_, _User ID_, _API key_