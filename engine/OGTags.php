<?php

class OGTags
{
    private $keywords;
    private $ogTitle;
    private $ogType;
    private $ogUrl;
    private $ogImage;
    private $ogImageAlt;
    private $ogSiteName;
    private $ogDescription;
    private $description;

    public function __construct($keywords, $ogTitle, $ogType, $ogUrl, $ogImage, $ogImageAlt, $ogSiteName, $ogDescription)
    {
        $this->keywords = $keywords;
        $this->ogTitle = $ogTitle;
        $this->ogType = $ogType;
        $this->ogUrl = $ogUrl;
        $this->ogImage = $ogImage;
        $this->ogImageAlt = $ogImageAlt;
        $this->ogSiteName = $ogSiteName;
        $this->ogDescription = $ogDescription;
        $this->description = $ogDescription;
    }

    public function generateTags()
    {
        $tags = '';

        // Generate meta tags for keywords
        if (!empty($this->keywords)) {
            $tags .= '<meta name="keywords" content="' . $this->keywords . '" />' . PHP_EOL;
        }

        // Generate Open Graph meta tags
        $ogTags = [
            'og:title' => $this->ogTitle,
            'og:type' => $this->ogType,
            'og:url' => $this->ogUrl,
            'og:image' => $this->ogImage,
            'og:image:alt' => $this->ogImageAlt,
            'og:site_name' => $this->ogSiteName,
            'og:description' => $this->ogDescription
        ];

        foreach ($ogTags as $property => $content) {
            $tags .= '<meta property="' . $property . '" content="' . $content . '" />' . PHP_EOL;
        }

        // Generate meta tag for description
        if (!empty($this->description)) {
            $tags .= '<meta name="description" content="' . $this->description . '">' . PHP_EOL;
        }

        return $tags;
    }
}

/* Example usage
$ograph = new OGTags(
    'Coding Ladies Club, Coding Ladies Academy',
    'Coding Ladies Academy',
    'website',
    'https://codingladies.org',
    'https://codingladies.org/assets/images/favicon.png',
    'icon',
    'Coding Ladies Academy',
    'Coding Ladies Academy is a pioneering educational platform dedicated to empowering women with cutting-edge coding skills and fostering their success in the ever-evolving tech industry.',
    'Coding Ladies Academy is a pioneering educational platform dedicated to empowering women with cutting-edge coding skills and fostering their success in the ever-evolving tech industry.'
);

echo $ograph->generateTags();
*/
