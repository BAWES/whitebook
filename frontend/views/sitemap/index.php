<?php 

use yii\helpers\Url;
header("Content-type: application/xml");
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
        href="<?= Url::to(['browse/list', 'slug' => $category['slug']], true); ?>>"
    />
    <xhtml:link
        rel="alternate"
        hreflang="ar-KW"
        href="<?= Url::to(['browse/list', 'slug' => $category['slug'],'language'=>'ar'], true); ?>"
    />
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
          href="<?= Url::to(['browse/detail', 'slug' => $item['slug'],'language'=>'ar'], true); ?>"
      />
    <priority>0.5</priority>
  </url>
  <?php } ?>
  <!-- vendors -->
  <?php foreach($vendors as $vendor) { ?>
  <url>
    <loc><![CDATA[<?= Url::to(['directory/profile', 'vendor' => $vendor['slug']], true); ?>]]></loc>
      <xhtml:link
          rel="alternate"
          hreflang="en"
          href="<?= Url::to(['directory/profile', 'vendor' => $vendor['slug']], true); ?>"
      />
      <xhtml:link
          rel="alternate"
          hreflang="ar-KW"
          href="<?= Url::to(['directory/profile', 'vendor' => $vendor['slug'],'language'=>'ar'], true); ?>"
      />
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
          href="<?= Url::to(['browse/list', 'slug' => 'all','language'=>'ar'], true); ?>"
      />
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
          href="<?= Url::to(['site/contact','language'=>'ar'], true); ?>"
      />
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
          href="<?= Url::to(['themes/index','language'=>'ar'], true); ?>"
      />
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
          href="<?= Url::to(['experience','language'=>'ar'], true); ?>"
      />
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
          href="<?= Url::to(['events/index', 'slug' => 'events','language'=>'ar'], true); ?>"
      />
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
          href="<?= Url::to(['/terms-conditions','language'=>'ar'], true); ?>"
      />
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
          href="<?= Url::to(['/about-us','language'=>'ar'], true); ?>"
      />
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
          href="<?= Url::to(['/privacy-policy','language'=>'ar'], true); ?>"
      />
    <priority>0.5</priority>
  </url>
</urlset>