CKEditor 4 Changelog
====================

## CKEditor 4.6.2

New Features:

* [#16733](http://dev.ckeditor.com/ticket/16733): Added a new pastel color palette for the [Color Button](http://ckeditor.com/addon/colorbutton) plugin and a new [`config.colorButton_colorsPerRow`](http://docs.ckeditor.com/#!/api/CKEDITOR.config-cfg-colorButton_colorsPerRow) configuration option for setting the number of rows in the color selector.
* [#16752](http://dev.ckeditor.com/ticket/16752): Added a new Azerbaijani localization. Thanks to the [Azerbaijani language team](https://www.transifex.com/ckeditor/teams/11143/az/)!
* [#13818](http://dev.ckeditor.com/ticket/13818): It is now possible to group [Widget](http://ckeditor.com/addon/widget) [style definitions](http://docs.ckeditor.com/#!/guide/dev_styles-section-widget-styles), so applying one style disables the other.

Fixed Issues:

* [#13446](http://dev.ckeditor.com/ticket/13446): [Chrome] Fixed: It is possible to type in an unfocused inline editor.
* [#14856](http://dev.ckeditor.com/ticket/14856): Fixed: [Font size and font family](http://ckeditor.com/addon/font) reset each other when modified at certain positions.
* [#16745](http://dev.ckeditor.com/ticket/16745): [Edge] Fixed: List items are lost when [pasted from Word](http://ckeditor.com/addon/pastefromword).
* [#16682](http://dev.ckeditor.com/ticket/16682): [Edge] Fixed: A list gets [pasted from Word](http://ckeditor.com/addon/pastefromword) as a set of paragraphs. Added the [`config.pasteFromWord_heuristicsEdgeList`](http://docs.ckeditor.com/#!/api/CKEDITOR.config-cfg-pasteFromWord_heuristicsEdgeList) configuration option.
* [#10373](http://dev.ckeditor.com/ticket/10373): Fixed: Context menu items can be dragged into the editor.
* [#16728](http://dev.ckeditor.com/ticket/16728): [IE] Fixed: [Copy Formatting](http://ckeditor.com/addon/copyformatting) breaks the editor in Quirks Mode.
* [#16795](http://dev.ckeditor.com/ticket/16795): [IE] Fixed: [Copy Formatting](http://ckeditor.com/addon/copyformatting) breaks the editor in Compatibility Mode.
* [#16675](http://dev.ckeditor.com/ticket/16675): Fixed: Styles applied with [Copy Formatting](http://ckeditor.com/addon/copyformatting) to a single table cell are applied to the whole table.
* [#16753](http://dev.ckeditor.com/ticket/16753): Fixed: [`element.setSize`](http://docs.ckeditor.com/#!/api/CKEDITOR.dom.element-method-setSize) sets incorrect editor dimensions if the border width is represented as a fraction of pixels.
* [#16705](http://dev.ckeditor.com/ticket/16705): [Firefox] Fixed: Unable to paste images as Base64 strings when using [Clipboard](http://ckeditor.com/addon/clipboard).
* [#14869](http://dev.ckeditor.com/ticket/14869): Fixed: JavaScript error is thrown when trying to use [Find](http://ckeditor.com/addon/find) in a [`<div>`-based editor](http://ckeditor.com/addon/divarea).

## CKEditor 4.6.1

New Features:

* [#16639](http://dev.ckeditor.com/ticket/16639): The `callback` parameter in the [CKEDITOR.ajax.post](http://docs.ckeditor.com/#!/api/CKEDITOR.ajax-method-post) method became optional.

Fixed Issues:

* [#11064](http://dev.ckeditor.com/ticket/11064): [Blink, WebKit] Fixed: Cannot select all editor content when a widget or a non-editable element is the first or last element of the content. Also fixes this issue in the [Select All](http://ckeditor.com/addon/selectall) plugin.
* [#14755](http://dev.ckeditor.com/ticket/14755): [Blink, WebKit, IE8] Fixed: Browser hangs when a table is inserted in the place of a selected list with an empty last item.
* [#16624](http://dev.ckeditor.com/ticket/16624): Fixed: Improved the [Color Button](http://ckeditor.com/addon/colorbutton) plugin which will now normalize the CSS `background` property if it only contains a color value. This fixes missing background colors when using [Paste from Word](http://ckeditor.com/addon/pastefromword).
* [#16600](http://dev.ckeditor.com/ticket/16600): [Blink, WebKit] Fixed: Error thrown occasionally by an uninitialized editable for multiple CKEditor instances on the same page.

## CKEditor 4.6

New Features:

* [#14569](http://dev.ckeditor.com/ticket/14569): Added a new, flat, default CKEditor skin called [Moono-Lisa](http://ckeditor.com/addon/moono-lisa). Refreshed default colors available in the [Color Button](http://ckeditor.com/addon/colorbutton) plugin ([Text Color and Background Color](http://docs.ckeditor.com/#!/guide/dev_colorbutton) feature).
* [#14707](http://dev.ckeditor.com/ticket/14707): Added a new [Copy Formatting](http://ckeditor.com/addon/copyformatting) feature to enable easy copying of styles between your document parts.
* Introduced the completely rewritten [Paste from Word](http://ckeditor.com/addon/pastefromword) plugin:
	* Backward incompatibility: The [`config.pasteFromWordRemoveFontStyles`](http://docs.ckeditor.com/#!/api/CKEDITOR.config-cfg-pasteFromWordRemoveFontStyles) option now defaults to `false`. This option will be deprecated in the future. Use [Advanced Content Filter](http://docs.ckeditor.com/#!/guide/dev_acf) to replicate the effect of setting it to `true`.
	* Backward incompatibility: The [`config.pasteFromWordNumberedHeadingToList`](http://docs.ckeditor.com/#!/api/CKEDITOR.config-cfg-pasteFromWordNumberedHeadingToList) and [`config.pasteFromWordRemoveStyles`](http://docs.ckeditor.com/#!/api/CKEDITOR.config-cfg-pasteFromWordRemoveStyles) options were dropped and no longer have any effect on pasted content.
	* Major improvements in preservation of list numbering, styling and indentation (nested lists with multiple levels).
	* Major improvements in document structure parsing that fix plenty of issues with distorted or missing content after paste.
* Added new translation: Occitan. Thanks to [Cédric Valmary](https://totenoc.eu/)!
* [#10015](http://dev.ckeditor.com/ticket/10015): Keyboard shortcuts (relevant to the operating system in use) will now be displayed in tooltips and context menus.
* [#13794](http://dev.ckeditor.com/ticket/13794): The [Upload Image](http://ckeditor.com/addon/uploadimage) feature now uses `uploaded.width/height` if set.
* [#12541](http://dev.ckeditor.com/ticket/12541): Added the [Upload File](http://ckeditor.com/addon/uploadfile) plugin that lets you upload a file by drag&amp;dropping it into the editor content.
* [#14449](http://dev.ckeditor.com/ticket/14449): Introduced the [Balloon Panel](http://ckeditor.com/addon/balloonpanel) plugin that lets you create stylish floating UI elements for the editor.
* [#12077](https://dev.ckeditor.com/ticket/12077): Added support for the HTML5 `download` attribute in link (`<a>`) elements. Selecting the "Force Download" checkbox in the [Link](http://ckeditor.com/addon/link) dialog will cause the linked file to be downloaded automatically. Thanks to [sbusse](https://github.com/sbusse)!
* [#13518](http://dev.ckeditor.com/ticket/13518): Introduced the [`additionalRequestParameters`](http://docs.ckeditor.com/#!/api/CKEDITOR.fileTools.uploadWidgetDefinition-property-additionalRequestParameters) property for file uploads to make it possible to send additional information about the uploaded file to the server.
* [#14889](http://dev.ckeditor.com/ticket/14889): Added the [`config.image2_altRequired`](http://docs.ckeditor.com/#!/api/CKEDITOR.config-cfg-image2_altRequired) option for the [Enhanced Image](http://ckeditor.com/addon/image2) plugin to allow making alternative text a mandatory field. Thanks to [Andrey Fedoseev](https://github.com/andreyfedoseev)!

Fixed Issues:

* [#9991](http://dev.ckeditor.com/ticket/9991): Fixed: [Paste from Word](http://ckeditor.com/addon/pastefromword) should only normalize input data.
* [#7209](http://dev.ckeditor.com/ticket/7209): Fixed: Lists with 3 levels not [pasted from Word](http://ckeditor.com/addon/pastefromword) correctly.
* [#14335](http://dev.ckeditor.com/ticket/14335): Fixed: Pasting a numbered list starting with a value different from "1" from Microsoft Word does not work correctly.
* [#14542](http://dev.ckeditor.com/ticket/14542): Fixed: Copying a numbered list from Microsoft Word does not preserve list formatting.
* [#14544](http://dev.ckeditor.com/ticket/14544): Fixed: Copying a nested list from Microsoft Word results in an empty list.
* [#14660](http://dev.ckeditor.com/ticket/14660): Fixed: [Pasting text from  Word](http://ckeditor.com/addon/pastefromword) breaks the styling in some cases.
* [#14867](http://dev.ckeditor.com/ticket/14867): [Firefox] Fixed: Text gets stripped when [pasting content from Word](http://ckeditor.com/addon/pastefromword).
* [#2507](http://dev.ckeditor.com/ticket/2507): Fixed: [Paste from Word](http://ckeditor.com/addon/pastefromword) does not detect pasting a part of a paragraph.
* [#3336](http://dev.ckeditor.com/ticket/3336): Fixed: Extra blank row added on top of the content [pasted from Word](http://ckeditor.com/addon/pastefromword).
* [#6115](http://dev.ckeditor.com/ticket/6115): Fixed: When Right-to-Left text direction is applied to a table [pasted from Word](http://ckeditor.com/addon/pastefromword), borders are missing on one side.
* [#6342](http://dev.ckeditor.com/ticket/6342): Fixed: [Paste from Word](http://ckeditor.com/addon/pastefromword) filters out a basic text style when it is [configured to use attributes](http://docs.ckeditor.com/#!/guide/dev_basicstyles-section-custom-basic-text-style-definition).
* [#6457](http://dev.ckeditor.com/ticket/6457): [IE] Fixed: [Pasting from Word](http://ckeditor.com/addon/pastefromword) is extremely slow.
* [#6789](http://dev.ckeditor.com/ticket/6789): Fixed: The `mso-list: ignore` style is not handled properly when [pasting from Word](http://ckeditor.com/addon/pastefromword).
* [#7262](http://dev.ckeditor.com/ticket/7262): Fixed: Lists in preformatted body disappear when [pasting from Word](http://ckeditor.com/addon/pastefromword).
* [#7662](http://dev.ckeditor.com/ticket/7662): [Opera] Fixed: Extra empty number/bullet shown in the editor body when editing a multi-level list [pasted from Word](http://ckeditor.com/addon/pastefromword).
* [#7807](http://dev.ckeditor.com/ticket/7807): Fixed: Last item in a list not converted to a `<li>` element after [pasting from Word](http://ckeditor.com/addon/pastefromword).
* [#7950](http://dev.ckeditor.com/ticket/7950): [IE] Fixed: Content [from Word pasted](http://ckeditor.com/addon/pastefromword) differently than in other browsers.
* [#7982](http://dev.ckeditor.com/ticket/7982): Fixed: Multi-level lists get split into smaller ones when [pasting from Word](http://ckeditor.com/addon/pastefromword).
* [#8231](http://dev.ckeditor.com/ticket/8231): [WebKit, Opera] Fixed: [Paste from Word](http://ckeditor.com/addon/pastefromword) inserts empty paragraphs.
* [#8266](http://dev.ckeditor.com/ticket/8266): Fixed: [Paste from Word](http://ckeditor.com/addon/pastefromword) inserts a blank line at the top.
* [#8341](http://dev.ckeditor.com/ticket/8341), [#7646](http://dev.ckeditor.com/ticket/7646): Fixed: Faulty removal of empty `<span>` elements in [Paste from Word](http://ckeditor.com/addon/pastefromword) content cleanup breaking content formatting.
* [#8754](http://dev.ckeditor.com/ticket/8754): [Firefox] Fixed: Incorrect pasting of multiple nested lists in [Paste from Word](http://ckeditor.com/addon/pastefromword).
* [#8983](http://dev.ckeditor.com/ticket/8983): Fixed: Alignment lost when [pasting from Word](http://ckeditor.com/addon/pastefromword) with [`config.enterMode`](http://docs.ckeditor.com/#!/api/CKEDITOR.config-cfg-enterMode) set to [`CKEDITOR.ENTER_BR`](http://docs.ckeditor.com/#!/api/CKEDITOR-property-ENTER_BR).
* [#9331](http://dev.ckeditor.com/ticket/9331): [IE] Fixed: [Pasting text from Word](http://ckeditor.com/addon/pastefromword) creates a simple Caesar cipher.
* [#9422](http://dev.ckeditor.com/ticket/9422): Fixed: [Paste from Word](http://ckeditor.com/addon/pastefromword) leaves an unwanted `color:windowtext` style.
* [#10011](http://dev.ckeditor.com/ticket/10011): [IE9-10] Fixed: [`config.pasteFromWordRemoveFontStyles`](http://docs.ckeditor.com/#!/api/CKEDITOR.config-cfg-pasteFromWordRemoveFontStyles) is ignored under certain conditions.
* [#10643](http://dev.ckeditor.com/ticket/10643): Fixed: Differences between using <kbd>Ctrl+V</kbd> and pasting from the [Paste from Word](http://ckeditor.com/addon/pastefromword) dialog.
* [#10784](http://dev.ckeditor.com/ticket/10784): Fixed: Lines missing when [pasting from Word](http://ckeditor.com/addon/pastefromword).
* [#11294](http://dev.ckeditor.com/ticket/11294): [IE10] Fixed: Font size is not preserved when [pasting from Word](http://ckeditor.com/addon/pastefromword).
* [#11627](http://dev.ckeditor.com/ticket/11627): Fixed: Missing words when [pasting from Word](http://ckeditor.com/addon/pastefromword).
* [#12784](http://dev.ckeditor.com/ticket/12784): Fixed: Bulleted list with custom bullets gets changed to a numbered list when [pasting from Word](http://ckeditor.com/addon/pastefromword).
* [#13174](http://dev.ckeditor.com/ticket/13174): Fixed: Data loss after [pasting from Word](http://ckeditor.com/addon/pastefromword).
* [#13828](http://dev.ckeditor.com/ticket/13828): Fixed: Widget classes should be added to the wrapper rather than the widget element.
* [#13829](http://dev.ckeditor.com/ticket/13829): Fixed: No class in [Widget](http://ckeditor.com/addon/widget) wrapper to identify the widget type.
* [#13519](http://dev.ckeditor.com/ticket/13519): Server response received when uploading files should be more flexible.

Other Changes:

* Updated [SCAYT](http://ckeditor.com/addon/scayt) (Spell Check As You Type) and [WebSpellChecker](http://ckeditor.com/addon/wsc) plugins:
 	* Support for the new default Moono-Lisa skin.
 	* [#121](https://github.com/WebSpellChecker/ckeditor-plugin-scayt/issues/121): Fixed: [Basic Styles](http://ckeditor.com/addon/basicstyles) do not work when SCAYT is enabled.
 	* [#125](https://github.com/WebSpellChecker/ckeditor-plugin-scayt/issues/125): Fixed: Inline styles are not continued when writing multiple lines of styled text with SCAYT enabled.
 	* [#127](https://github.com/WebSpellChecker/ckeditor-plugin-scayt/issues/127): Fixed: Uncaught TypeError after enabling SCAYT in the CKEditor `<div>` element.
 	* [#128](https://github.com/WebSpellChecker/ckeditor-plugin-scayt/issues/128): Fixed: Error thrown after enabling SCAYT caused by conflicts with RequireJS.

## CKEditor 4.5.11

**Security Updates:**

* [Severity: minor] Fixed the `target="_blank"` vulnerability reported by James Gaskell.

	Issue summary: If a victim had access to a spoofed version of ckeditor.com via HTTP (e.g. due to DNS spoofing, using a hacked public network or mailicious hotspot), then when using a link to the ckeditor.com website it was possible for the attacker to change the current URL of the opening page, even if the opening page was protected with SSL.

  An upgrade is recommended.

New Features:

* [#14747](http://dev.ckeditor.com/ticket/14747): The [Enhanced Image](http://ckeditor.com/addon/image2) caption now supports the link `target` attribute.
* [#7154](http://dev.ckeditor.com/ticket/7154): Added support for the "Display Text" field to the [Link](http://ckeditor.com/addon/link) dialog. Thanks to [Ryan Guill](https://github.com/ryanguill)!

Fixed Issues:

* [#13362](http://dev.ckeditor.com/ticket/13362): [Blink, WebKit] Fixed: Active widget element is not cached when it is losing focus and it is inside an editable element.
* [#13755](http://dev.ckeditor.com/ticket/13755): [Edge] Fixed: Pasting images does not work.
* [#13548](http://dev.ckeditor.com/ticket/13548): [IE] Fixed: Clicking the [elements path](http://ckeditor.com/addon/elementspath) disables Cut and Copy icons.
* [#13812](http://dev.ckeditor.com/ticket/13812): Fixed: When aborting file upload the placeholder for image is left.
* [#14659](http://dev.ckeditor.com/ticket/14659): [Blink] Fixed: Content scrolled to the top after closing the dialog in a [`<div>`-based editor](http://ckeditor.com/addon/divarea).
* [#14825](http://dev.ckeditor.com/ticket/14825): [Edge] Fixed: Focusing the editor causes unwanted scrolling due to dropped support for the `setActive` method.

## CKEditor 4.5.10

Fixed Issues:

* [#10750](http://dev.ckeditor.com/ticket/10750): Fixed: The editor does not escape the `font-style` family property correctly, removing quotes and whitespace from font names.
* [#14413](http://dev.ckeditor.com/ticket/14413): Fixed: The [Auto Grow](http://ckeditor.com/addon/autogrow) plugin with the [`config.autoGrow_onStartup`](http://docs.ckeditor.com/#!/api/CKEDITOR.config-cfg-autoGrow_onStartup) option set to `true` does not work properly for an editor that is not visible.
* [#14451](http://dev.ckeditor.com/ticket/14451): Fixed: Numeric element ID not escaped properly. Thanks to [Jakub Chalupa](https://github.com/chaluja7)!
* [#14590](http://dev.ckeditor.com/ticket/14590): Fixed: Additional line break appearing after inline elements when switching modes. Thanks to [dpidcock](https://github.com/dpidcock)!
* [#14539](https://dev.ckeditor.com/ticket/14539): Fixed: JAWS reads "selected Blank" instead of "selected <widget name>" when selecting a widget.
* [#14701](http://dev.ckeditor.com/ticket/14701): Fixed: More precise labels for [Enhanced Image](http://ckeditor.com/addon/image2) and [Placeholder](http://ckeditor.com/addon/placeholder) widgets.
* [#14667](http://dev.ckeditor.com/ticket/14667): [IE] Fixed: Removing background color from selected text removes background color from the whole paragraph.
* [#14252](http://dev.ckeditor.com/ticket/14252): [IE] Fixed: Styles drop-down list does not always reflect the current style of the text line.
* [#14275](http://dev.ckeditor.com/ticket/14275): [IE9+] Fixed: `onerror` and `onload` events are not used in browsers it could have been used when loading scripts dynamically.

## CKEditor 4.5.9

Fixed Issues:

* [#10685](http://dev.ckeditor.com/ticket/10685): Fixed: Unreadable toolbar icons after updating to the new editor version. Fixed with [6876179](https://github.com/ckeditor/ckeditor-dev/commit/6876179db4ee97e786b07b8fd72e6b4120732185) in [ckeditor-dev](https://github.com/ckeditor/ckeditor-dev) and [6c9189f4](https://github.com/ckeditor/ckeditor-presets/commit/6c9189f46392d2c126854fe8889b820b8c76d291) in [ckeditor-presets](https://github.com/ckeditor/ckeditor-presets).
* [#14573](https://dev.ckeditor.com/ticket/14573): Fixed: Missing [Widget](http://ckeditor.com/addon/widget) drag handler CSS when there are multiple editor instances.
* [#14620](https://dev.ckeditor.com/ticket/14620): Fixed: Setting both the `min-height` style for the `<body>` element and the `height` style for the `<html>` element breaks the [Auto Grow](http://ckeditor.com/addon/autogrow) plugin.
* [#14538](http://dev.ckeditor.com/ticket/14538): Fixed: Keyboard focus goes into an embedded `<iframe>` element.
* [#14602](http://dev.ckeditor.com/ticket/14602): Fixed: The [`dom.element.removeAttribute()`](http://docs.ckeditor.com/#!/api/CKEDITOR.dom.element-method-removeAttribute) method does not remove all attributes if no parameter is given.
* [#8679](http://dev.ckeditor.com/ticket/8679): Fixed: Better focus indication and ability to style the selected color in the [color picker dialog](http://ckeditor.com/addon/colordialog).
* [#11697](http://dev.ckeditor.com/ticket/11697): Fixed: Content is replaced ignoring the letter case setting in the [Find and Replace](http://ckeditor.com/addon/find) dialog window.
* [#13886](http://dev.ckeditor.com/ticket/13886): Fixed: Invalid handling of the [`CKEDITOR.style`](http://docs.ckeditor.com/#!/api/CKEDITOR.style) instance with the `styles` property by [`CKEDITOR.filter`](http://docs.ckeditor.com/#!/api/CKEDITOR.filter).
* [#14535](http://dev.ckeditor.com/ticket/14535): Fixed: CSS syntax corrections. Thanks to [mdjdenormandie](https://github.com/mdjdenormandie)!

## CKEditor 4.5.8

New Features:

* [#12440](http://dev.ckeditor.com/ticket/12440): Added the [`config.colorButton_enableAutomatic`](http://docs.ckeditor.com/#!/api/CKEDITOR.config-cfg-colorButton_enableAutomatic) option to allow hiding the "Automatic" option in the [color picker](http://ckeditor.com/addon/colorbutton).

Fixed Issues:

* [#10448](http://dev.ckeditor.com/ticket/10448): Fixed: Lack of scrollbar in the [right-to-left text direction](http://ckeditor.com/addon/bidi).
* [#12707](http://dev.ckeditor.com/ticket/12707): Fixed: The order of table elements does not comply with the HTML specification.
* [#13756](http://dev.ckeditor.com/ticket/13756): [Edge] Fixed: Context menus are cut-off.

## CKEditor 4.5.7

New Features:

* [#14327](http://dev.ckeditor.com/ticket/14327): Added Swiss German localization. Thanks to [Miro Grenda](https://twitter.com/mirogrenda)!

Fixed Issues:

* [#13816](http://dev.ckeditor.com/ticket/13816): Introduced a new strategy for Filling Character handling to avoid changes in DOM. This fixes the following issues:
	* [#12727](http://dev.ckeditor.com/ticket/12727): [Blink] `IndexSizeError` when using the [Div Editing Area](http://ckeditor.com/addon/divarea) and [Content Templates](http://ckeditor.com/addon/templates) plugins.
	* [#13377](http://dev.ckeditor.com/ticket/13377): [Widget](http://ckeditor.com/addon/widget) plugin issue when typing in Korean.
	* [#13389](http://dev.ckeditor.com/ticket/13389): [Blink] [`editor.getData()`](http://docs.ckeditor.com/#!/api/CKEDITOR.editor-method-getData) fails when the cursor is next to an `<hr>` tag.
	* [#13513](http://dev.ckeditor.com/ticket/13513): [Blink, WebKit] [Div Editing Area](http://ckeditor.com/addon/divarea) and [`editor.getData()`](http://docs.ckeditor.com/#!/api/CKEDITOR.editor-method-getData) throw an error when an image is the only data in the editor.
* [#13884](http://dev.ckeditor.com/ticket/13884): [Firefox] Fixed: Copying and pasting a table results in just the first cell being pasted.
* [#14234](http://dev.ckeditor.com/ticket/14234): Fixed: URL input field is not marked as required in the [Media Embed](http://ckeditor.com/addon/embed) dialog.

## CKEditor 4.5.6

New Features:

* Introduced the [`CKEDITOR.tools.getCookie()`](http://docs.ckeditor.com/#!/api/CKEDITOR.tools-method-getCookie) and [`CKEDITOR.tools.setCookie()`](http://docs.ckeditor.com/#!/api/CKEDITOR.tools-method-setCookie) methods for accessing cookies.
* Introduced the [`CKEDITOR.tools.getCsrfToken()`](http://docs.ckeditor.com/#