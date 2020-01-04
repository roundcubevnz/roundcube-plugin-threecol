
Roundcube Webmail ThreeCol
==========================

This plugin adds an option to the mailbox settings to enable the user to
display the preview pane either to the right had side of the message list or
below it. **This plugins was already integrated into roundcube 1.3.0 release** 
so this plugins are for 0.9, 1.1 and 1.2 roudcube versions only.

Jaime_Pomales created a patch to give the default skin a three column layout
this patch was the inspiration for the plugin and also provided some of the
changes which need to be made to the default skin


## Install

* Place this plugins files into plugins directory of Roundcube as `threecol`
* Add "`threecol`" to `$rcmail_config['plugins']` in your Roundcube config

## Configuration

To make enable the three column layout as default for all users set
`$rcmail_config['previewpane_layout'] = 'right';`
in the main config file. To prevent users from changing this setting add
`previewpane_layout` to the `dont_override` config option

Colums have problemsm so then recommended set to only 
`$config['list_cols'] = array('flag', 'status', 'subject', 'fromto', 'date', 'attachment');`, 
in older skins and rc versions the only resising column are the subject so 
you maybe need to hacked the css and rc skin code.

If you have some extra skins, maybe you will need to duplicate the `larry` skin 
inside the plugins directory to eacho one skin name, to solve the view skin problems.

## Authors

*Plugin forked from t3chguy/Roundcube-Plugin-Threecol-Layout*

*Plugin originally created by @JohnDoh.*

License
=======

This plugin is released under the GNU General Public License Version 3
(http://www.gnu.org/licenses/gpl-3.0.html).

Even if skins might contain some programming work, they are not considered
as a linked part of the plugin and therefore skins DO NOT fall under the
provisions of the GPL license. See the README file located in the core skins
folder for details on the skin license.

