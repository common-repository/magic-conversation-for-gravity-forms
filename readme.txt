=== Magic Conversation For Gravity Forms ===

Contributors: magicplugins
Tags: conversational form, contact form, responsive, mobile friendly
Requires at least: 3.2
Tested up to: 6.5
Stable tag: 3.0.94
Stable tag: trunk
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Magic Conversation For Gravity Forms is a WordPress conversational form plugin that let's you convert a Gravity Form into a conversational form.

== Description ==

Magic Conversation For Gravity Forms is a WordPress conversational form plugin that let's you convert a Gravity Form into a conversational form.

Our plugin is very easy to use. No programming is required.

Just install and activate and then you can convert a Gravity Form into a conversational web form.

Magic Conversation For Gravity Forms, visit our website:
http://magicconversation.net

Check out our Screencaps page:
http://magicconversation.net/screencaps/

Check out our Demos:

1. Travel While You Work
https://magicconversation.net/magic-conversation/8/

2. Standard Fields Demo
https://magicconversation.net/magic-conversation/9/

To learn how to use Magic Conversation For Gravity Forms plugin, visit our Online Documentation:
http://magicconversation.net/documentation/

Magic Conversation For Gravity Forms features:

- Global conversation button - your conversation form button is displayed on all pages of your site.

- Customizable Conversation Toolbar - set default text that users will see in the chat input field. Customize the button color.

- Set to display the conversation button only on the home page.

- Display a customizable welcome message on the conversation button. 

- Modify the background color of the conversation button.

- Conversation style generator - choose from a selection of chat avatars for both the form robot and the user.

Premium versions are available at http://magicconversation.net/pricing

- Enable conditional logic with the premium version.

- Create multiple forms in conversation mode.
 
- Display conversation forms in a page.

== Installation ==

1. Upload the plugin files to the /wp-content/plugins/magic-conversation-for-gravity-forms directory, or install the plugin through the WordPress plugins screen directly.

2. Activate the plugin through the \'Plugins\' screen in WordPress

== Quick Start ==

- Show a Conversation Button in Home page or the whole website.

Settings -> Conversation Form -> Choose a form

- Embed a conversation into post/page with short code

[magic-conversation id="1" width="100%" height="395px"]

- Add Floating Conversation Button to special page with short code

[magic-conversation-button id="1"]

- Trigger conversation with link

&lt;a href="/open-magic-conversation?form_id=1"&gt;Open Conversation&lt;/a&gt;

- Trigger conversation with JavaScript code

window.mcfgf_open_magic_conversation("/open-magic-conversation?form_id=1");

Example:

&lt;button onclick="window.mcfgf_open_magic_conversation('open-magic-conversation?form_id=1');return false;"&gt;Open Conversation&lt;/button&gt;

== Frequently Asked Questions ==

Q. Does Magic Conversation work with other form plugins? 

A.  Not at this time.


Q. Can I use my own CSS?

A. Yes, you can use your own custom CSS. Please follow the instructions located on our Custom CSS Instructions page here: http://magicconversation.net/custom-css-instructions/


Q. Does Magic Conversation work with WordPress Multisite?

A. Yes, Magic Conversation works with WordPress Multisite.


== Screenshots ==

1. A view of the global conversation button.
2. A view of the global conversation active status.
3. A view of the embed conversation.
4. A view of the plugin conversation style generator page.
5. A view of the plugin settings page.

== Changelog ==

Version v3.0.94
    Added support of WordPress 6.5.

Version v3.0.93
    Added support of WordPress 6.1.

Version v3.0.92
    Added support of WordPress 6.0.

Version v3.0.91
    Added support of WordPress 5.9.

Version v3.0.90
    Fixed issue on handling merged tags when hidden fields are in front of visible fields.

Version v3.0.89
    Fixed issue on loading conversation for some form.

Version v3.0.88
    Fixed issue on continue conversation after three rounds that occurs in v3.0.83. (Fix 2)

Version v3.0.87
    Fixed issue on continue conversation after three rounds that occurs in v3.0.83. (Fix 1)

Version v3.0.86
    Fixed issue on auto scroll conversation to the bottom that may prevent user see the new question directly.

Version v3.0.85
    Added support of enable options filter for Drop Down, Radio Buttons, Option and Checkboxes field.

Version v3.0.84
    Added support of allow field to be populated dynamically with specified parameter name.

Version v3.0.83
    Fixed issue on handling click event of radio button icon.

Version v3.0.82
    Added support of jump to another form by use mc-jump-{form_id} as confirmation message.
    Fixed issue on conditional confirmation.

Version v3.0.81
    Added support of GravityForms 2.5.

Version v3.0.80
    Fixed issue on loading plugin when GravityForms plugin is not installed or activated.

Version v3.0.79
    Added support of display as form when the global conversation button is clicked. (Fix)

Version v3.0.78
    Added support of display as form when the global conversation button is clicked.
    Added support of decode merge tags in Welcome Page Template.
    Fixed issue on insert merge tags via the side icon button into Welcome Page Template.

Version v3.0.77
    Added support of auto detect browser locale for formatting date.
    Added support of set locale in settings page for formatting date.

Version v3.0.76
    Try to fix issue of showing warning messsage if there is no category in site.

Version v3.0.75
    Added support of show conversation only in specified categories.

Version v3.0.74
    Added support of Chained Select field.
    Fixed issue on validating File Upload field as required field.
    Fixed issue on showing Consent field.

Version v3.0.73
    Added welcome mode to route multiple conversation.

Version v3.0.72
    Added reload button besides toggle fullscreen button.

Version v3.0.71
    Added button to toggle full screen for conversation opened by global conversation button.
    Added reload button to reset conversation that opened by global conversation button.

Version v3.0.70
    Added support of apply custom css in conversation chat area.

Version v3.0.69
    Added Quick Start guide to Help page.
    Added welcome message tooltip background and font color support.
    Added support of configure and show merge tags in welcome message.
    Added support of configure and show merge tags in conversation header.
    Moved welcome message and conversation header content configuration to Forms -> Edit -> Settings -> Conversaton.
    Fixed issue on open conversation when user clicks welcome message.
    Fixed issue on hide conversation header when user disabled conversation header
    Fixed issue on hide welcome message when user disabled welcome message

Version v3.0.68
    Replace screenshots to latest version. (Fix 2)

Version v3.0.67
    Replace screenshots to latest version. (Fix 1)

Version v3.0.66
    Fixed issue on embed conversation with short code.

Version v3.0.65
    Added support of filter options when user input keywords for Radio or Drop Down fields. 

Version v3.0.64
    Added support of display conversation button for special form via short code.

Version v3.0.63
    Fixed issue on saving user input value for Paragraph Text field.
    Fixed issue on auto resize composer after user send response for Paragraph Text field. 

Version v3.0.62
    Fixed issue on conversation just hangs after user answer first question (v3.0.61 only).

Version v3.0.61
    Fixed issue on displaying conversation in mobile browser.
    Fixed issue on opening sub form automatically when Name or Address field get clicked.

Version v3.0.60
    Fixed issue on preventing normal form confirmation redirect in AJAX mode.

Version v3.0.59
    Added support of add custom CSS Class of field to specified question.

Version v3.0.58
    Added support of auto focus and keep focus on textarea input.

Version v3.0.57
    Added support of auto focus on textarea input.

Version v3.0.56
    Fixed issue on changing color of user messages with Conversation Style Generator.

Version v3.0.55
    Added support of show image style checkbox and radio group.
    Added support of Product field with 'Hidden' field type.
    Fixed issue on conditional logic calculation for Checkbox field and Option field with 'Checkboxes' field type.
    Fixed issue on showing deplay response status.
    Fixed issue on display price for Option field with 'Drop Down' or 'Radio Buttons' field type.
    Fixed issue on showing price with symbol.

Version v3.0.54
    Fixed issue on conditional logic calculation.
    Fixed issue on conversation scrolling.

Version v3.0.53
    Fixed issue on display toolbar in iOS 13.2.

Version v3.0.52
    Fixed issue on conditional logic calculation.

Version v3.0.51
    Added support of enter name directly when Name field contains only one input.
    Fixed issue of display plain text when enter password in Password field.

Version v3.0.50
    Fixed issue that date picker will auto closing when click right arrow to change month.
    Added support of click DONE btton with Enter key stroke for Email, Name and Address field.

Version v3.0.49
    Fixed issue on total price calculation.

Version v3.0.48
    Fixed issue on field validation for the last field.
    Changed redirect in same window as default redirect option.

Version v3.0.47
    Fixed issue on Email field that with Email Confirmation disabled.
    
Version v3.0.46
    Added support of GF Mollie by Indigo plugin.

Version v3.0.45
    Added support of Gravity Forms User Registration Add-On.

Version v3.0.44
    Fixed issue on calculating total price for Product field with radio options.
    Fixed issue on redirect in same window.

Version v3.0.43
    Fixed issue of showing border for embed conversation.

Version v3.0.42
    Fixed issue of use date scroller as Date picker.

Version v3.0.41
    Added support of use calendar as Date picker.
    Added support of show confirmation message as normal form submission.
    Added support of Consent field.
    Fixed issue of showing scrollbar on loading conversation.

Version v3.0.40
    Added support of use default value in Formula calculation.
    Added support of rounding in Formula calculation.
    Added support of dynamically resize text input (textarea).
    Added support of pre populate value with query parameter.

Version v3.0.39
    Added support of set up image button size in settings page.
    Fixed issue of auto submit form after bypassing fields that requires no user input, such as HTML field.
    Fixed issue on Image button mode.

Version v3.0.38
    Added support of Formula calculation for Number field.
    Added support of multiple confirmations with conditional logic.
    Fixed issue on conditional logic comparation on 'greater than' and 'less than'.

Version v3.0.37
    Fixed issue of displaying embed conversation on mobile.

Version v3.0.36
    Added close button for global conversation on desktop view.

Version v3.0.35
    Added version check.

Version v3.0.34
    Fixed conflict issue on Themify Builder.

Version v3.0.33
    Added Autoptimize compatibility support. (Fix 2)

Version v3.0.32
    Added Autoptimize compatibility support.

Version v3.0.31
    Fixed issue on live update check.

Version v3.0.30
    Added license subscription support.

Version v3.0.29
    Make the conditional logic rule 'is' same as 'contains' for Checkbox field.
    Fixed issue of display checked status for Checkbox field.
    Added support of change month names for Date field.

Version v3.0.28
    Fixed issue on Date field with custom date format.

Version v3.0.27
    Fixed issue on loading conversation if test on local server or domain contains localhost.

Version v3.0.26
    Fixed issue that conversation scrollbar overlap the bottom toolbar.
    Added support of customize Send button text.
    Added support of use fullsize image for Global Conversation Button.
    Show Send button instead of Skip button when field is required.

Version v3.0.25
    Added support of Font Awesome 5 icon for Skip button.
    Added support of customize waiting for response hint.

Version v3.0.24
    Added support of open conversation by passing form data with query string on a form submission.

Version v3.0.23
    Added support of 'Allow field to be populated dynamically' for radio group field.

Version v3.0.22
    Fixed issue on showing text input in composer area that made it hard to focus.

Version v3.0.21
    Added support of upload multiple files.

Version v3.0.20
    Added support of GravityForms Stripe add-on (Gravity Forms + Stripe).
    Added WooCommerce Product Picker Generator.

Version v3.0.19
    Added support of setup Border Width and Border Color of message bubble with Conversation Style Generator.
    Added support of Wizard mode for Multi-Page forms.
    Added support of post submission to 3rd party Rest API with JSON Submission Data Template.
    Added support of post submission (Window.postMessage) to parent window with JSON Submission Data Template (case when embed as iframe).
    Added support of notify height to parent window when the conversation scroll height changes.
    Added support of add Merged Tags into JSON Submission Data Template.


Version v3.0.18
    Added support of GravityForms Paypal add-on.

Version v3.0.17
    Added support of conversation settings of show/hide input toolbar.
    Added support of conversation settings of auto confirm by click option for the Radio field.

Version v3.0.16
    Fixed issue on loading conversation via short code.

Version v3.0.15
    Add options for handling confirmation page or redirect.

Version v3.0.14
    Fixed issue on skipping Name or Address field.
    Fixed issue on stopping working when press ENTER key in Phone field.
    Added support of allowing send Textarea field content by pressing ENTER key.
    Added Conversation item in Gravity Forms' form actions menu at forms listing page and Wordpress Admin Bar.
    Added Conversation item in the edit form toolbar menu.

Version v3.0.13
    Fixed issue on display conversation on Firefox.

Version v3.0.12
    Added support of Product field.
    Added support of Quantity field.
    Added support of Option field.
    Added support of Shipping field.
    Added support of Total field.
    Added support of showing maximum file size for Fileupload field.
    Fixed issue on displaying image outside of message bubble.

Version v3.0.11
    Fixed issue on auto fill default country, state or province in Address picker.
    Fixed issue on keeping previous state in Address picker.
    Fixed issue on keeping previous state in Name picker.
    Added support of showing Conversation Permalink in Gravity Forms list page.
    Added support of Password field.

Version v3.0.10
    Removed loading of code for v2.x

Version v3.0.9
    Fixed issue on showing link preview.

Version v3.0.8
    Added support for adding Merge Tag with Visual Picker just like Gravity Forms conformation message page do.
    Added support for show preview of confirmation page or redirect.
    Added support for skip a whole page of fields with Page Conditional Logic

Version v3.0.7
    Added support for view conversation directly with permalink.

Version v3.0.6
    Added Delayed Response Control feature.

Version v3.0.5
    Added support for adding shortcodes with the visual form picker as GravityForms.
    Added toggle for fullsreen button for embedded conversations.

Version v2.0.27
    Added support for adding conversation via short code.
    Added support of Popup Maker plugin.
    Added support for showing conversation in post.
    Added support for trigger conversation via javascript.
    Added support for showing conversation via thick box. 
    Added support for trigger conversation via button or link click.
    Added help content for short code.
    Prevent Auto load multiple conversations

Version v2.0.26
    Fixed issue on email input validation if Output HTML5 settings of Gravity Forms is turned on.

Version v2.0.25
    Fixed issue on email input validation.

Version v2.0.24
    Fixed issue on submission if rewind conversation enabled after confirmation
    Added support for auto focus user input for Global Conversation.

Version v2.0.23
    Added support for Multi-Page forms.
    Added support for bypass the Page and Section field.

Version v2.0.22
    Added support for bypass the field configured as Hidden in Visibility.

Version v2.0.21
    Fixed issue on displaying Merge Tags.

Version v2.0.20
    Fixed compatible issue on PHP 4.4.

Version v2.0.19
    Fixed display issue on Chrome browser.

Version v2.0.18
    Fixed paragraph display issue.

Version v2.0.17
    Added support of Survey Add-On.

Version v2.0.16
    Added support of User Registration Add-On.

Version v2.0.15
    Added support of enable automatic fullscreen mode in mobile devices. 
    Fixed issue on saving custom questions in form field settings.

Version v2.0.14
    Fixed issue on submit conversation form in WP website subfolder installation

Version v2.0.13
    Fixed issue that rewind feature stops working on global conversation button.

Version v2.0.12
    Mobile UI supported.

Version v2.0.11
    Fixed issue on showing questions in lower case.
    Fixed issue on submit form when rewind feature is not enabled.

Version v2.0.10
    Fixed issue on rewind feature.

Version v2.0.9
    Added support for rewind to be beginning of conversation with Conditional Logic settings.

Version v2.0.8
    Added support of using Wordpress Visual Editor in HTML field.

Version v2.0.7
    Fixed issue on Select field support in Conditional Logic.

Version v2.0.6
    Added support of Short Code and embed video in Wordpress Visual Editor.

Version v2.0.5
    Added support to show questions in HTML format.
    Added support to edit questions with Wordpress Visual Editor.

Version v2.0.4
    Added support to Gravity Forms Feature: Enable enhanced user interface.

Version v2.0.3
    Fixed select field support of Conditional Logic support.

Version v2.0.2
    Added support of Pricing fields.

Version v2.0.1
    Stable Release of Conditional Logic support. (Premium version only)

Version v1.0.46
    Added support of Conditional Confirmation Message.

Version v1.0.45
    Added support of Merge Tags for checkbox group and radio group.

Version v1.0.44

    Added support of Merge Tags that makes it possible to use client's previous inputs in next questions. (Premium version only)

Version v1.0.43

    Fixed issue on Conditional Logic support. (Premium version only)

Version v1.0.42

    Fixed issue that checkbox group will loose checked status on first option.

Version v1.0.41

    Added Conditional Logic support. (Premium version only)

Version v1.0.40

    Fixed some issue.

Version v1.0.39

    Remove hint of WeDevs_Settings_API class.

Version v1.0.38

    Added support for customize continue button text and width for checkbox options.

Version v1.0.37

    Added support for showing a Done button for checkbox group.
    Fixed issue on submission when there is a hidden field in the end of form.

Version v1.0.36

    Added support of change conversation container height in form settings page.
    Fixed issue on displaying form when conversation is not finished loading.
    Fixed issue that when not all fields are set required, forms get submitted multiple times when user finished conversation.

Version v1.0.35

    Fixed display issue on help tooltip.

Version v1.0.34

    Fixed issue on checkbox support.

Version v1.0.33

    Fixed conversation preview display issue on Conversation Style Generator page.

Version v1.0.32

    Fixed text display issue after change from input to textarea.

Version v1.0.31
	
	Fixed chat input placeholder revert back to default settings from the second question.

Version v1.0.30
	
	Fixed conflict of plugin update checker with other magic plugins.(v4)

Version v1.0.29
    
    Now supports PHP 5.3.

Version v1.0.28
    
    Fixed conflict of plugin update checker with other magic plugins.

Version v1.0.27

    Added custom questions support in Gravity Forms field settings.

Version v1.0.26

    Added Upgrade to Premumium Link.

Version v1.0.25

    Changed readme.txt.

Version v1.0.24

    Fixed issue on First Name and Last Name validation.
    
== Upgrade Notice ==