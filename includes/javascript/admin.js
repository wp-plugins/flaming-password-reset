jQuery(document).ready(function(){

    /**
     * When an input has changed, then we need to display the "Changes have been made" warning.
     */
    jQuery('input[type="checkbox"], input[type="text"], input[type="radio"], textarea').change(DisplayChangesWarning);

    /**
     * When an editor has changed, then we need to display the "Changes have been made" warning.
     */
    if (tinymce) {
        tinymce.EditorManager.on('AddEditor', function (event) {
            event.editor.on('change', function(){
                DisplayChangesWarning();
            });
        });
    }
});

/**
 * Hide the Success/Error messages and display the "Changes have been made" warning.
 */
function DisplayChangesWarning()
{
    jQuery('.flaming_success, .flaming_error').css('display', 'none');
    jQuery('.flaming_warning').css('display', 'block');
}

/**
 * Displays an alert box that gives a warning about how resetting the settings cannot be undone!
 */
function DisplayResetWarning()
{
    return confirm("Resetting your Settings will return them to their default values. This cannot be undone. Are you sure that you want to continue?");
}

/**
 * Insert a placeholder into the specified text editor.
 *
 * @param Placeholder
 * @param TextEditorId
 */
function AddPlaceholder(Placeholder, TextEditorId)
{
    var Editor = tinymce.get(TextEditorId);
    if (Editor) {
        Editor.execCommand('mceInsertContent', false, Placeholder);
    }
}