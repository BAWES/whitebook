<?php 

use yii\helpers\Url;

?>
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.google.com/schemas/sitemap/0.90">
  <!-- categories -->
  <?php foreach($categories as $category) { ?>
  <url>
    <loc><![CDATA[<?= Url::to(['browse/list', 'slug' => $category['slug']], true); ?>]]></loc>
    <changefreq>daily</changefreq>
    <priority>0.5</priority>
  </url>
  <?php } ?>
  <!-- items -->
  <?php foreach($items as $item) { ?>
  <url>
    <loc><![CDATA[<?= Url::to(['browse/detail', 'slug' => $item['slug']], true); ?>]]></loc>
    <changefreq>daily</changefreq>
    <priority>0.5</priority>
  </url>
  <?php } ?>
  <!-- vendors -->
  <?php foreach($vendors as $vendor) { ?>
  <url>
    <loc><![CDATA[<?= Url::to(['directory/profile', 'vendor' => $vendor['slug'], 'slug' => 'all'], true); ?>]]></loc>
    <changefreq>daily</changefreq>
    <priority>0.5</priority>
  </url>
  <?php } ?>
  <!-- other pages -->
  <url>
    <loc><![CDATA[<?= Url::to(['browse/list', 'slug' => 'all'], true); ?>]]></loc>
    <changefreq>daily</changefreq>
    <priority>0.5</priority>
  </url>
  <url>
    <loc><![CDATA[<?= Url::to(['site/contact'], true); ?>]]></loc>
    <changefreq>daily</changefreq>
    <priority>0.5</priority>
  </url>
  <url>
    <loc><![CDATA[<?= Url::to(['themes/index'], true); ?>]]></loc>
    <changefreq>daily</changefreq>
    <priority>0.5</priority>
  </url>
  <url>
    <loc><![CDATA[<?= Url::to(['/experience'], true); ?>]]></loc>
    <changefreq>daily</changefreq>
    <priority>0.5</priority>
  </url>
  <url>
    <loc><![CDATA[<?= Url::to(['events/index', 'slug' => 'events'], true); ?>]]></loc>
    <changefreq>daily</changefreq>
    <priority>0.5</priority>
  </url>
  <url>
    <loc><![CDATA[<?= Url::to(['/terms-conditions'], true); ?>]]></loc>
    <changefreq>daily</changefreq>
    <priority>0.5</priority>
  </url>
  <url>
    <loc><![CDATA[<?= Url::to(['/about-us'], true); ?>]]></loc>
    <changefreq>daily</changefreq>
    <priority>0.5</priority>
  </url>
  <url>
    <loc><![CDATA[<?= Url::to(['/privacy-policy'], true); ?>]]></loc>
    <changefreq>daily</changefreq>
    <priority>0.5</priority>
  </url>
</urlset>