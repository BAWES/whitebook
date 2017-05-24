<a data-toggle="dropdown">
    <?php echo Yii::t('frontend', 'Cart ({count})', ['count' => count($items)]); ?>
</a> 
<minibag-dropdown class="minibag-dropdown">
    <div class="minibag-dropdown-content minibag-dropdown-content--double">
        <div class="minibag-overflow-container">
            <minibag-item-list class="minibag-item-list">
                <ul class="bag-items">
                    <li class="bag-item-holder" data-remove-text="Item deleted">
                    <div class="bag-item-padding">
                    <div class="bag-item-border">

                        <minibag-item-product class="bag-item bag-item--product">
                            <minibag-item-image class="bag-item-image">
                                <a href="http://www.asos.com/prd/7916351">
                                    <img class="bag-item-image-img" src="https://images.asos-media.com/inv/media/0/2/2/6/7916220/black/image1l.jpg?wid=100" />
                                </a>
                            </minibag-item-image>
                            <div class="bag-item-descriptions">
                                <minibag-price class="bag-item-price">
                                    <p class="bag-item-price-holder">
                                        <span class="bag-item-price bag-item-price--current">£10.00</span>
                                    </p>
                                </minibag-price>
                                <p class="bag-item-name">
                                    <a>ASOS Culotte Shorts</a>
                                </p>
                                <p class="bag-item-variants">
                                    <span class="bag-item-variant bag-item-variant--colour" data-bind="text: item.colour">Black</span>
                                    <span class="bag-item-variant bag-item-variant--size" data-bind="text: item.size">UK 12</span>
                                </p>
                                <p class="bag-item-quantity">
                                    <span data-bind="miniBagLocaleText: 'minibag-item-quantity-prefix'">Qty</span> <span class="bag-item-variant bag-item-variant--quantity" data-bind="text: item.quantity">1</span>
                                </p>
                            </div>
                        </minibag-item-product>
                        <minibag-remove class="bag-item-remove-holder" params="item: $data"><button class="bag-item-remove" data-bind="click: removeItem, attr: {'data-id': item.id, 'title': removeIconTextLocalised }" data-id="ffd57140-eebd-49c0-92fc-82b0ce09afb1" title="Delete this item"></button>
                        </minibag-remove>
                    </div>
                    </div>
                    </li>
                </ul>
            </minibag-item-list>
            <div class="minibag-meta-container">
                <minibag-sub-total class="minibag-subtotal" params="price: summary.totalPrice.text">
                    <div class="minibag-subtotal-holder">
                        <h3 class="minibag-subtotal-subtotal">
                            <span class="minibag-subtotal-title" data-bind="
                            miniBagLocaleText: 'minibag-dropdown-subtotal'
                            ">Sub-total</span>
                            <!-- ko if: displayVatMessage --><!-- /ko -->
                            <span class="minibag-subtotal-price" data-bind="text: price">£40.00</span>
                        </h3>
                    </div>
                </minibag-sub-total>
                <p class="minibag-bag-buttons">
                    <span class="minibag-button-holder minibag-button-holder--view-bag">
                        <a class="minibag-button minibag-button--view-bag">
                            <span class="minibag-button-text-content">VIEW BAG</span>
                        </a>
                    </span>
                    <span class="minibag-button-holder minibag-button-holder--checkout">
                        <a class="minibag-button minibag-button--checkout">
                            <span class="minibag-button-text-content">CHECKOUT</span>
                        </a>
                    </span>
                </p>
            </div>
        </div>
    </div>
</minibag-dropdown>