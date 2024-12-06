# Enhanced Dynamic Author Widget

The **Enhanced Dynamic Author Widget** plugin dynamically displays the **current post's author avatar**, **biography**, and a list of their posts in a customizable widget. It also includes options to display **post dates**, **categories**, and more.

This plugin is perfect for multi-author blogs, business websites, or any WordPress site that wants to highlight authors and their contributions.

---

## Features

### Core Features
- **Author Avatar and Bio**: Displays the author's profile picture and bio with a customizable "Read More" link.
- **Post List Display**:
  - **Latest Posts**: Shows the most recent posts by the author.
  - **Random Posts**: Displays a random selection of the author's posts.
- **Post Metadata**: Optional display of post publication dates and categories.

### Customization
- Configure the avatar size.
- Set the maximum bio length.
- Choose the number of posts to display.
- Remove bullet points and align the widget content.

### Additional Enhancements
- Fully responsive and styled to match most themes.
- Debugging messages for unsupported pages (e.g., archive pages).
- Designed for single post and page views.

---

## Installation

### Step 1: Install the Plugin
1. Download the plugin as a `.zip` file.
2. Log in to your WordPress Admin Dashboard.
3. Navigate to **Plugins > Add New**.
4. Click **Upload Plugin** and select the `.zip` file.
5. Click **Install Now**, then **Activate** the plugin.

### Step 2: Add the Widget
1. Go to **Appearance > Widgets**.
2. Add the **Enhanced Dynamic Author Widget** to your desired widget area (e.g., Sidebar).
3. The widget automatically adapts to the current post's author.

---

## Configuration

### Step 1: Plugin Settings
Navigate to **Settings > Dynamic Author Widget** to configure global settings:

1. **Avatar Size**: Set the size of the author's avatar (default: 100px).
2. **Max Bio Length**: Define the maximum number of characters for the author's biography.
3. **Post Display Type**: Choose to display either:
   - **Latest Posts**
   - **Random Posts**
4. **Number of Posts**: Specify how many posts to display (default: 5).
5. **Post Metadata**:
   - Enable/disable **post dates**.
   - Enable/disable **post categories**.

### Step 2: Widget Display
- The widget will dynamically display:
  - Author's avatar and name.
  - Author's truncated biography (with "Read More" link).
  - A list of their posts.

---

## Styling

The plugin includes basic styling that aligns the widget content and removes bullet points. You can further customize it with CSS. Below are the default styles:

```css
.author-widget {
    text-align: center;
}

.author-widget ul {
    list-style: none;
    padding-left: 0;
}

.author-widget ul li {
    margin-bottom: 10px;
}

.author-widget .author-avatar img {
    border-radius: 50%;
    margin: 0 auto;
}



---

Thank you for exploring these plugins! If you have any feedback or suggestions, feel free to share them in the respective GitHub repositories. 😊

![Alt text](https://github.com/childtheme/codesupple/blob/dynamic-author-widget/result.jpg)
![Alt text](https://github.com/childtheme/codesupple/blob/dynamic-author-widget/settings.jpg)
