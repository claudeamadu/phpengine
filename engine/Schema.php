<?php

class Schema
{
    private static function formatDateTime($dateTime)
    {
        $dateTime = new DateTime($dateTime);
        return $dateTime->format('Y-m-d\TH:i:sP');
    }


    public static function Article($title, $description, $author, $datePublished, $image)
    {
        echo "<script type=\"application/ld+json\">" . json_encode([
            "@context" => "https://schema.org/",
            "@type" => "Article",
            "headline" => $title,
            "description" => $description,
            "author" => $author,
            "datePublished" => self::formatDateTime($datePublished),
            "image" => $image
        ]) . "</script>";
    }

    public static function Breadcrumb($items)
    {
        $breadcrumbList = [
            "@context" => "https://schema.org/",
            "@type" => "BreadcrumbList",
            "itemListElement" => []
        ];

        foreach ($items as $position => $item) {
            $breadcrumbList["itemListElement"][] = [
                "@type" => "ListItem",
                "position" => $position,
                "name" => $item['name'],
                "item" => $item['item']
            ];
        }

        echo "<script type=\"application/ld+json\">" . str_replace('\/','/',json_encode($breadcrumbList)) . "</script>";
    }

    public static function Event($name, $startDate, $endDate, $location)
    {
        echo "<script type=\"application/ld+json\">" . json_encode([
            "@context" => "https://schema.org/",
            "@type" => "Event",
            "name" => $name,
            "startDate" => self::formatDateTime($startDate),
            "endDate" => self::formatDateTime($endDate),
            "location" => [
                "@type" => "Place",
                "name" => $location
            ]
        ]) . "</script>";
    }

    public static function FAQPage($questions)
    {
        $faqPage = [
            "@context" => "https://schema.org/",
            "@type" => "FAQPage",
            "mainEntity" => []
        ];

        foreach ($questions as $question => $answer) {
            $faqPage["mainEntity"][] = [
                "@type" => "Question",
                "name" => $question,
                "acceptedAnswer" => [
                    "@type" => "Answer",
                    "text" => $answer
                ]
            ];
        }

        echo "<script type=\"application/ld+json\">" . json_encode($faqPage) . "</script>";
    }

    public static function HowTo($name, $steps)
    {
        $howTo = [
            "@context" => "https://schema.org/",
            "@type" => "HowTo",
            "name" => $name,
            "step" => []
        ];

        foreach ($steps as $step) {
            $howTo["step"][] = [
                "@type" => "HowToStep",
                "text" => $step
            ];
        }

        echo "<script type=\"application/ld+json\">" . json_encode($howTo) . "</script>";
    }

    public static function JobPosting($title, $description, $datePosted, $validThrough, $hiringOrganization)
    {
        echo "<script type=\"application/ld+json\">" . json_encode([
            "@context" => "https://schema.org/",
            "@type" => "JobPosting",
            "title" => $title,
            "description" => $description,
            "datePosted" => $datePosted,
            "validThrough" => $validThrough,
            "hiringOrganization" => [
                "@type" => "Organization",
                "name" => $hiringOrganization
            ]
        ]) . "</script>";
    }

    public static function LocalBusiness($name, $address, $telephone, $url, $image, $priceRange)
    {
        echo "<script type=\"application/ld+json\">" . json_encode([
            "@context" => "https://schema.org/",
            "@type" => "LocalBusiness",
            "name" => $name,
			"image" => $image,
			"priceRange" => $priceRange,
            "address" => [
                "@type" => "PostalAddress",
                "streetAddress" => $address['streetAddress'],
                "addressLocality" => $address['addressLocality'],
                "addressRegion" => $address['addressRegion'],
                "postalCode" => $address['postalCode'],
                "addressCountry" => $address['addressCountry']
            ],
            "telephone" => $telephone,
            "url" => $url
        ]) . "</script>";
    }

    public static function Organization($name, $description, $url, $logo, $email, $phone, $address)
    {
        echo "<script type=\"application/ld+json\">" . json_encode([
            "@context" => "https://schema.org/",
            "@type" => "Organization",
            "name" => $name,
            "description" => $description,
            "url" => $url,
            "logo" => $logo,
            "image" => $logo,
			"email" => $email,
			"telephone" => $phone,
			"address" => [
				"@type" => "PostalAddress",
                "streetAddress" => $address['streetAddress'],
                "addressLocality" => $address['addressLocality'],
                "addressRegion" => $address['addressRegion'],
                "postalCode" => $address['postalCode'],
                "addressCountry" => $address['addressCountry']
				]
        ]) . "</script>";
    }

    public static function Person($name, $jobTitle, $image)
    {
        echo "<script type=\"application/ld+json\">" . json_encode([
            "@context" => "https://schema.org/",
            "@type" => "Person",
            "name" => $name,
            "jobTitle" => $jobTitle,
            "image" => $image
        ]) . "</script>";
    }

    public static function Recipe($name, $description, $ingredients, $instructions, $image)
    {
        echo "<script type=\"application/ld+json\">" . json_encode([
            "@context" => "https://schema.org/",
            "@type" => "Recipe",
            "name" => $name,
            "description" => $description,
            "recipeIngredient" => $ingredients,
            "recipeInstructions" => $instructions,
            "image" => $image
        ]) . "</script>";
    }

    public static function Video($name, $description, $thumbnailUrl, $uploadDate)
    {
        echo "<script type=\"application/ld+json\">" . json_encode([
            "@context" => "https://schema.org/",
            "@type" => "VideoObject",
            "name" => $name,
            "description" => $description,
            "thumbnailUrl" => $thumbnailUrl,
            "uploadDate" => self::formatDateTime($uploadDate)
        ]) . "</script>";
    }

    public static function Website($name, $url)
    {
        echo "<script type=\"application/ld+json\">" . json_encode([
            "@context" => "https://schema.org/",
            "@type" => "WebSite",
            "name" => $name,
            "url" => $url
        ]) . "</script>";
    }

    public static function Course($name, $description, $provider, $providerUrl)
    {
        echo "<script type=\"application/ld+json\">" . json_encode([
            "@context" => "https://schema.org/",
            "@type" => "Course",
            "name" => $name,
            "description" => $description,
            "provider" => [
                "@type" => "Organization",
                "name" => $provider,
                "sameAs" => $providerUrl
            ]
        ]) . "</script>";
    }

    public static function Carousel($items)
    {
        $carousel = [
            "@context" => "https://schema.org/",
            "@type" => "ItemList",
            "itemListElement" => []
        ];

        foreach ($items as $position => $item) {
            $carousel["itemListElement"][] = [
                "@type" => "ListItem",
                "position" => $position + 1,
                "item" => [
                    "@type" => "WebPage",
                    "url" => $item['url'],
                    "name" => $item['name']
                ]
            ];
        }

        echo "<script type=\"application/ld+json\">" . json_encode($carousel) . "</script>";
    }

    public static function CourseList($courses)
    {
        $courseList = [
            "@context" => "https://schema.org/",
            "@type" => "ItemList",
            "itemListElement" => []
        ];

        foreach ($courses as $position => $course) {
            $courseList["itemListElement"][] = [
                "@type" => "ListItem",
                "position" => $position + 1,
                "url" => $course['url'],
                "name" => $course['name']
            ];
        }

        echo "<script type=\"application/ld+json\">" . json_encode($courseList) . "</script>";
    }

    public static function CourseInfo($name, $description, $provider, $providerUrl)
    {
        echo "<script type=\"application/ld+json\">" . json_encode([
            "@context" => "https://schema.org/",
            "@type" => "Course",
            "name" => $name,
            "description" => $description,
            "provider" => [
                "@type" => "Organization",
                "name" => $provider,
                "sameAs" => $providerUrl
            ]
        ]) . "</script>";
    }

    public static function LearningVideo($name, $description, $thumbnailUrl, $uploadDate)
    {
        echo "<script type=\"application/ld+json\">" . json_encode([
            "@context" => "https://schema.org/",
            "@type" => ["VideoObject", "LearningResource"],
            "name" => $name,
            "description" => $description,
            "thumbnailUrl" => $thumbnailUrl,
            "uploadDate" => self::formatDateTime($uploadDate)
        ]) . "</script>";
    }

    public static function Product($name, $description, $brand, $image, $sku, $url, $offers)
    {
        echo "<script type=\"application/ld+json\">" . json_encode([
            "@context" => "https://schema.org/",
            "@type" => "Product",
            "name" => $name,
            "description" => $description,
            "brand" => $brand,
            "image" => $image,
            "sku" => $sku,
            "url" => $url,
            "offers" => $offers
        ]) . "</script>";
    }

    public static function SoftwareApplication($name, $description, $operatingSystem, $applicationCategory, $downloadUrl, $screenshot)
    {
        echo "<script type=\"application/ld+json\">" . json_encode([
            "@context" => "https://schema.org/",
            "@type" => "SoftwareApplication",
            "name" => $name,
            "description" => $description,
            "operatingSystem" => $operatingSystem,
            "applicationCategory" => $applicationCategory,
            "downloadUrl" => $downloadUrl,
            "screenshot" => $screenshot
        ]) . "</script>";
    }

    public static function ProfilePage($name, $url, $description, $mainEntityOfPage)
    {
        echo "<script type=\"application/ld+json\">" . json_encode([
            "@context" => "https://schema.org/",
            "@type" => "ProfilePage",
            "name" => $name,
            "url" => $url,
            "description" => $description,
            "mainEntityOfPage" => $mainEntityOfPage
        ]) . "</script>";
    }

    public static function ReviewSnippet($itemReviewed, $reviews)
    {
        $reviewSnippet = [
            "@context" => "https://schema.org/",
            "@type" => "Review",
            "itemReviewed" => $itemReviewed,
            "review" => []
        ];

        foreach ($reviews as $review) {
            $reviewSnippet["review"][] = [
                "@type" => "Review",
                "reviewRating" => [
                    "@type" => "Rating",
                    "ratingValue" => $review['ratingValue']
                ],
                "author" => [
                    "@type" => "Person",
                    "name" => $review['author']
                ],
                "datePublished" => self::formatDateTime($review['datePublished']),
                "reviewBody" => $review['reviewBody']
            ];
        }

        echo "<script type=\"application/ld+json\">" . json_encode($reviewSnippet) . "</script>";
    }
}

/* Example usage
$articleSchema = Schema::Article(
    "Sample Article Title",
    "Sample article description.",
    "John Doe",
    "2024-04-23T08:00:00Z",
    "https://example.com/article.jpg"
);

$breadcrumbSchema = Schema::Breadcrumb([
    ["name" => "Coding Ladies Academy", "item" => "https://academy.codingladies.org"],
    ["name" => "Coding Ladies | Coding Ladies Academy", "item" => "https://academy.codingladies.org/"]
]);

$eventSchema = Schema::Event(
    "Sample Event",
    "2024-04-25T08:00:00Z",
    "2024-04-26T17:00:00Z",
    "Sample Location"
);

$faqSchema = Schema::FAQPage([
    "Question 1?" => "Answer 1.",
    "Question 2?" => "Answer 2."
]);

$howToSchema = Schema::HowTo(
    "Sample How-To",
    ["Step 1: Do this.", "Step 2: Do that."]
);

$jobPostingSchema = Schema::JobPosting(
    "Sample Job",
    "Sample job description.",
    "2024-04-23",
    "2024-05-23",
    "Sample Organization"
);

$localBusinessSchema = Schema::LocalBusiness(
    "Sample Business",
    [
        "streetAddress" => "123 Sample St",
        "addressLocality" => "Sample City",
        "addressRegion" => "Sample State",
        "postalCode" => "12345",
        "addressCountry" => "Sample Country"
    ],
    "123-456-7890",
    "https://example.com"
);

$organizationSchema = Schema::Organization(
    "Sample Organization",
    "Sample organization description.",
    "https://example.com",
    "https://example.com/logo.jpg"
);

$personSchema = Schema::Person(
    "John Doe",
    "Software Engineer",
    "https://example.com/john.jpg"
);

$recipeSchema = Schema::Recipe(
    "Sample Recipe",
    "Sample recipe description.",
    ["Ingredient 1", "Ingredient 2"],
    ["Step 1: Do this.", "Step 2: Do that."],
    "https://example.com/recipe.jpg"
);

$videoSchema = Schema::Video(
    "Sample Video",
    "Sample video description.",
    "https://example.com/video.jpg",
    "2024-04-23"
);

$websiteSchema = Schema::Website(
    "Sample Website",
    "https://example.com"
);

$courseSchema = Schema::Course(
    "Sample Course",
    "Sample course description.",
    "Sample Provider",
    "https://example.com/provider"
);

$carouselSchema = Schema::Carousel([
    ["name" => "Item 1", "url" => "https://example.com/item1"],
    ["name" => "Item 2", "url" => "https://example.com/item2"]
]);

$courseListSchema = Schema::CourseList([
    ["name" => "Course 1", "url" => "https://example.com/course1"],
    ["name" => "Course 2", "url" => "https://example.com/course2"]
]);

$courseInfoSchema = Schema::CourseInfo(
    "Sample Course",
    "Sample course description.",
    "Sample Provider",
    "https://example.com/provider"
);

$learningVideoSchema = Schema::LearningVideo(
    "Sample Video",
    "Sample video description.",
    "https://example.com/video.jpg",
    "2024-04-23"
);

$productSchema = Schema::Product(
    "Sample Product",
    "Sample product description.",
    "Sample Brand",
    "https://example.com/product.jpg",
    "12345",
    "https://example.com/product",
    [
        "@type" => "Offer",
        "price" => "100.00",
        "priceCurrency" => "USD"
    ]
);

$softwareApplicationSchema = Schema::SoftwareApplication(
    "Sample App",
    "Sample app description.",
    "Android",
    "Application",
    "https://example.com/app",
    "https://example.com/app_screenshot.jpg"
);

$profilePageSchema = Schema::ProfilePage(
    "John Doe",
    "https://example.com/profile",
    "Profile description.",
    "https://example.com/profile/about"
);

$reviewSnippetSchema = Schema::ReviewSnippet(
    "Sample Item",
    [
        [
            "ratingValue" => "5",
            "author" => "John Doe",
            "datePublished" => "2024-04-23",
            "reviewBody" => "Great item!"
        ],
        [
            "ratingValue" => "4",
            "author" => "Jane Smith",
            "datePublished" => "2024-04-24",
            "reviewBody" => "Good item!"
        ]
    ]
);

echo $articleSchema;
echo $breadcrumbSchema;
echo $eventSchema;
echo $faqSchema;
echo $howToSchema;
echo $jobPostingSchema;
echo $localBusinessSchema;
echo $organizationSchema;
echo $personSchema;
echo $recipeSchema;
echo $videoSchema;
echo $websiteSchema;
echo $courseSchema;
echo $carouselSchema;
echo $courseListSchema;
echo $courseInfoSchema;
echo $learningVideoSchema;
echo $productSchema;
echo $softwareApplicationSchema;
echo $profilePageSchema;
echo $reviewSnippetSchema;
*/