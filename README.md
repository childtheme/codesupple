# Custom Header for Elementor (Free Version)

A WordPress plugin that allows you to set a custom header using the free version of Elementor.

## ⚠️ Disclaimer  

> **Please Note**: I am **not an official developer of CodeSupply themes**. These plugins are provided "**as is**" and come with the following considerations:  
> 
> ### Important Notice  
> - **Backup Requirement**: Ensure you create a full backup of your site before using any of these plugins.  
> - **Usage Agreement**: If you disagree with this disclaimer, please refrain from using these plugins on your site.  
>
> **Liability**: I am not responsible for any issues, plugin conflicts, or malfunctions that may arise. Additionally, I do not provide support for these plugins.  

---

## ⭐ Features
- Assign any Elementor template as a custom header.
- Works with the free version of Elementor.
- Easy-to-use settings page.
- Hides the default theme header automatically.

## 🔧 Installation

### 📌 Manual Installation:
1. Download the plugin as a `.zip` file.
2. Go to your WordPress dashboard → **Plugins** → **Add New**.
3. Click **Upload Plugin** and select the downloaded `.zip` file.
4. Click **Install Now**, then **Activate** the plugin.

### 📌 FTP Installation:
1. Extract the `.zip` file.
2. Upload the `custom-header-elementor` folder to the `wp-content/plugins/` directory.
3. Go to your WordPress dashboard → **Plugins**, find **Custom Header for Elementor**, and click **Activate**.

## 🚀 How to Use
### 1️⃣ Create a Header Template in Elementor:
- Go to `Elementor → Templates → Saved Templates`.
- Click `Add New`, choose `Section`, and design your header.
- Publish the template.

### 2️⃣ Assign the Header Template in Plugin Settings:
- Navigate to `Settings → Custom Header`.
- Select your saved Elementor template from the dropdown list.
- Click `Save Header Template`.

Your Elementor template will now be used as your website's custom header! 🎉

Use this free Elementor header template pack:
🔗 https://elebuilds.com/free-elementor-footer-templates-pack-1

## 🎨 Hiding the Default Theme Header
To ensure only your custom Elementor header is displayed, the plugin automatically applies the following CSS:

```css
.cs-header {
    display: none;
}
.cs-header-before {
    display: none;
}
