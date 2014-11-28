WPML ACF Relations
==================

Aims on WordPress sites with both WPML and ACF plugin installed.

When you duplicate a post in [WPML](http://wpml.org/) Relational Fields created with [Advanced Custom Fields](http://advancedcustomfields.com/) will not change to their corresponding translations. This can be annoying, when you have large image galeries that you want to translate as well.

This little plugin fills the gap by replacing all ACF relations with their translated version.

Tested with
- Advanced Custom Fields 5 (Pro)
- WPML Multilingual CMS (3.1.8.3)

Currently supported ACF Fields:
- Content / Image
- Content / File
- Content / Gallery
- Relational / Post Object
- Relational / Page Link
- Relational / Relationship

Untested (but likely unsupported):
- Layout / Repeater

Unsupported fields:
- Relational / Taxonomy
- Relational / User

Will you answer Support requests?
---------------------------------
Sorry, I won't. 

I made this plugin for a client's job, where I had to find a solution for the problem described above. 
I made it public in the hope that it may be useful for others. 

If it doesn't work for you, make your changes, and send me a pull request if you think that others can benefit as well.
