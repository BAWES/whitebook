<?php 

use yii\helpers\Url;
header ("Content-Type:text/xml");
?>
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:xhtml="http://www.w3.org/1999/xhtml">
  <!-- categories -->
  <?php foreach($categories as $category) { ?>
  <url>
    <loc><![CDATA[<?= Url::to(['browse/list', 'slug' => $category['slug']], true); ?>]]></loc>

    <xhtml:link
        rel="alternate"
        hreflang="en"
        href="<?= Url::to(['browse/list', 'slug' => $category['slug']], true); ?>"
    />
    <xhtml:link
        rel="alternate"
        hreflang="ar-KW"
        href="<?= Url::to(['/ar/browse/list', 'slug' => $category['slug']], true); ?>"
    />

    <changefreq>daily</changefreq>
    <priority>0.5</priority>
  </url>
  <?php } ?>
  <!-- items -->
  <?php foreach($items as $item) { ?>
  <url>
    <loc><![CDATA[<?= Url::to(['browse/detail', 'slug' => $item['slug']], true); ?>]]></loc>

    <xhtml:link
        rel="alternate"
        hreflang="en"
        href="<?= Url::to(['browse/detail', 'slug' => $item['slug']], true); ?>"
    />

    <xhtml:link
        rel="alternate"
        hreflang="ar-KW"
        href="<?= Url::to(['/ar/browse/detail', 'slug' => $item['slug']], true); ?>"
    />

    <changefreq>daily</changefreq>
    <priority>0.5</priority>
  </url>
  <?php } ?>
  <!-- vendors -->
  <?php foreach($vendors as $vendor) { ?>
  <url>
    <loc><![CDATA[<?= Url::to(['directory/profile', 'vendor' => $vendor['slug'], 'slug' => 'all'], true); ?>]]></loc>

    <xhtml:link
        rel="alternate"
        hreflang="en"
        href="<?= Url::to(['directory/profile', 'vendor' => $vendor['slug'], 'slug' => 'all'], true); ?>"
    />

    <xhtml:link
        rel="alternate"
        hreflang="ar-KW"
        href="<?= Url::to(['/ar/directory/profile', 'vendor' => $vendor['slug'], 'slug' => 'all'], true); ?>"
    />

    <changefreq>daily</changefreq>
    <priority>0.5</priority>
  </url>
  <?php } ?>
  <!-- other pages -->
  <url>
    <loc><![CDATA[<?= Url::to(['browse/list', 'slug' => 'all'], true); ?>]]></loc>

    <xhtml:link
        rel="alternate"
        hreflang="en"
        href="<?= Url::to(['browse/list', 'slug' => 'all'], true); ?>"
    />

    <xhtml:link
        rel="alternate"
        hreflang="ar-KW"
        href="<?= Url::to(['/ar/browse/list', 'slug' => 'all'], true); ?>"
    />

    <changefreq>daily</changefreq>
    <priority>0.5</priority>
  </url>
  <url>
    <loc><![CDATA[<?= Url::to(['site/contact'], true); ?>]]></loc>

    <xhtml:link
        rel="alternate"
        hreflang="en"
        href="<?= Url::to(['site/contact'], true); ?>"
    />

    <xhtml:link
        rel="alternate"
        hreflang="ar-KW"
        href="<?= Url::to(['/ar/site/contact'], true); ?>"
    />

    <changefreq>daily</changefreq>
    <priority>0.5</priority>
  </url>
  <url>
    <loc><![CDATA[<?= Url::to(['themes/index'], true); ?>]]></loc>
    <xhtml:link
        rel="alternate"
        hreflang="en"
        href="<?= Url::to(['themes/index'], true); ?>"
    />

    <xhtml:link
        rel="alternate"
        hreflang="ar-KW"
        href="<?= Url::to(['/ar/themes/index'], true); ?>"
    />

    <changefreq>daily</changefreq>
    <priority>0.5</priority>
  </url>
  <url>
    <loc><![CDATA[<?= Url::to(['/experience'], true); ?>]]></loc>

    <xhtml:link
        rel="alternate"
        hreflang="en"
        href="<?= Url::to(['/experience'], true); ?>"
    />

    <xhtml:link
        rel="alternate"
        hreflang="ar-KW"
        href="<?= Url::to(['/ar/experience'], true); ?>"
    />

    <changefreq>daily</changefreq>
    <priority>0.5</priority>
  </url>
  <url>
    <loc><![CDATA[<?= Url::to(['events/index', 'slug' => 'events'], true); ?>]]></loc>

    <xhtml:link
        rel="alternate"
        hreflang="en"
        href="<?= Url::to(['events/index', 'slug' => 'events'], true); ?>"
    />

    <xhtml:link
        rel="alternate"
        hreflang="ar-KW"
        href="<?= Url::to(['/ar/events/index', 'slug' => 'events'], true); ?>"
    />

    <changefreq>daily</changefreq>
    <priority>0.5</priority>
  </url>
  <url>
    <loc><![CDATA[<?= Url::to(['/terms-conditions'], true); ?>]]></loc>
    <xhtml:link
        rel="alternate"
        hreflang="en"
        href="<?= Url::to(['/terms-conditions'], true); ?>"
    />

    <xhtml:link
        rel="alternate"
        hreflang="ar-KW"
        href="<?= Url::to(['/ar/terms-conditions'], true); ?>"
    />

    <changefreq>daily</changefreq>
    <priority>0.5</priority>
  </url>
  <url>
    <loc><![CDATA[<?= Url::to(['/about-us'], true); ?>]]></loc>
    <xhtml:link
        rel="alternate"
        hreflang="en"
        href="<?= Url::to(['/about-us'], true); ?>"
    />

    <xhtml:link
        rel="alternate"
        hreflang="ar-KW"
        href="<?= Url::to(['/ar/about-us'], true); ?>"
    />

    <changefreq>daily</changefreq>
    <priority>0.5</priority>
  </url>
  <url>
    <loc><![CDATA[<?= Url::to(['/privacy-policy'], true); ?>]]></loc>

    <xhtml:link
        rel="alternate"
        hreflang="en"
        href="<?= Url::to(['/privacy-policy'], true); ?>"
    />

    <xhtml:link
        rel="alternate"
        hreflang="ar-KW"
        href="<?= Url::to(['/ar/privacy-policy'], true); ?>"
    />

    <changefreq>daily</changefreq>
    <priority>0.5</priority>
  </url>
</urlset>