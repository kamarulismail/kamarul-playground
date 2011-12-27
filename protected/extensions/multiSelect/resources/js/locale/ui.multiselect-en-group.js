/**
 * Localization strings for the UI Multiselect widget
 *
 * @locale en, en-US
 */

$.extend($.ui.multiselect.locale, {
	addAll:'Add all',
	removeAll:'Remove all',
	itemsCount:'#{count} member(s) in the group',
	itemsTotal:'#{count} members total',
    instruction:'Double click or click + to add member',
	busy:'please wait...',
	errorDataFormat:"Cannot add options, unknown data format",
	errorInsertNode:"There was a problem trying to add the item:\n\n\t[#{key}] => #{value}\n\nThe operation was aborted.",
	errorReadonly:"The option #{option} is readonly",
	errorRequest:"Sorry! There seemed to be a problem with the remote call. (Type: #{status})"
});