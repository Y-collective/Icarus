# Icarus

> Integrates Fly Dynamic Image Resizer with WP Smush.

Fly Dynamic Image Resizer generates images on the fly. Those generated images never pass through WP Smush automatically, thus are left unoptimized.

This plugin creates a bridge between the two, by automatically piping dynamically resized and generated images from Fly Dynamic Image Resizer through WP Smush's optimization function.


## Requirements

* [Fly Dynamic Image Resizer](https://wordpress.org/plugins/fly-dynamic-image-resizer/) >= 1.0.5
* [WP Smush](https://wordpress.org/plugins/wp-smushit/) >= 2.6.0


## Installation
1. Copy the `icarus` folder to `wp-content/plugins`.
2. Activate the plugin through the WordPress admin panel.


## Usage

There is no GUI for this plugin, since all it does is that under the hood it just hooks on Fly's action which is fired upon the creation of a new image, and feeds the generated image's file path to WP Smush. That's it.


## Our Company

We are a Hungary-based digital agency, working with a range of clients across Europe on cutting edge digital projects. Check out or website at [y-collective.com](http://y-collective.com).


## License
GPL-2.0+ © [Y-collective](http://y-collective.com)
