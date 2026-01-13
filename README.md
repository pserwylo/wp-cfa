# Wordpress CFA Plugin (WP_CFA)

A set of tools to help Country Fire Authority (CFA) brigades in Victoria, Australia
to display the following on their WordPress websites:
* Fire danger ratings
* Weather observations from the Bureau of Meteorology
* Current date + time

This is done by providing the following shortcodes:

* `[cfa_fire_danger_rating_text]`
* `[cfa_fire_danger_rating_image_url]`
* `[cfa_fire_danger_rating_image_tag]`
* `[cfa_weather_observation_temperature]`
* `[cfa_weather_observation_temperature_rounded]`
* `[cfa_weather_observation_temperature_number]`
* `[cfa_date_time]`

## Usage

### Fire danger ratings

Ensure you have selected your correct fire district from Settings -> WP CFA -> District.

### Weather observations

### Date and time

## Example

### Variable message screen

If you have a variable message screen (VMS) outside your station, you will want to create an empty page with
no headers, menus, or footers, and then add the relevant shortcodes with some minor styling:

#### Current danger rating

```html
<!-- Align this size exactly to your VMS resolution for best outcomes -->
<div style="width: 650px; height=250px">
	<div style="font-size: 25px">Today's fire danger rating is:</div>
	<div>[cfa_fire_danger_rating_text]</div>
	<div>[cfa_fire_danger_rating_image_tag]</div>
</div>
```
