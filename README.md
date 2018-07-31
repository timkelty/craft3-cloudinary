Cloudinary for Craft CMS 3
=======================
This plugin is an integration of the cloud-based image managment  [cloudinary](https://cloudinary.com/) to your Craft3 project.

## Installation
### Cloudinary
1. Create a free [cloudinary account](https://cloudinary.com/)
2. Logged in to your account and go to the cloudinary dashboard
3. On top you can see your credentials (Cloud Name, API Key and API Secret). Copy them

### Install the plugin
1. `composer require timkelty/craft3-cloudinary`
2. Go to your Craft admin panel --> Settings --> Plugins and install the Cloudinary plugin

### Setup up the volume
3. In the Craft admin panel, go to Settings -> Assets
4. Create a new volume
5. Type in a name you like (f.e. `Cloud Images`)
6. Enable `Assets in this volume have public URLs`
7. You can decide on your Base URL (f.e. `/images` if you want to have `http://yourwebsite.com/images/` as your public image path)
8. Volume Type must be `Cloudinary`
9. Now fill in the Cloudinary credentials
10. Go to the Assets and upload the first image
![Craft Cloudinary asset volume settings](https://res.cloudinary.com/dsteinel/image/upload/v1532443782/craft-cloudinary-asset-volume-settings.png)
