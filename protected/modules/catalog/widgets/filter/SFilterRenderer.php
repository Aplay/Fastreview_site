<?php

/**
 * Base class to render filters by:
 *  Manufacturer
 *  Price
 *  Eav attributes
 *
 * Usage:
 * $this->widget('application.modules.store.widgets.SFilterRenderer', array(
 *      // Categories model. Used to create url
 *      'model'=>$model,
 *  ));
 *
 * @method CategoryController getOwner()
 */
class SFilterRenderer extends CWidget
{

    /**
     * @var array of ProductOptions models
     */
    public $attributes;

    /**
     * @var Categories
     */
    public $model;
        public $allmodel;
        public $rootcat;
        public $setcat;
        public $active = array();
        public $per_page;

    /**
     * @var array html option to apply to `Clear attributes` link
     */
    public $clearLinkOptions = array('class'=>'clearOptions');

    /**
     * @var array of options to apply to 'active filters' menu
     */
    public $activeFiltersHtmlOptions = array('class'=>'filter_links current');

    /**
     * @var string default view to render results
     */
    public $view = 'modern2';
        public $itemView;
        public $allowedPageLimit = array();
        public $seller;
        /**
     * @var string min price in the query
     */
    private $_currentMinPrice = null;

    /**
     * @var string max price in the query
     */
    private $_currentMaxPrice = null;
        
    /**
     * Render filters
     */
    public function run()
    {
               $activeFilters = $this->getActiveFilters($this->rootcat);
               $this->render($this->view, array(
                        'categories'=>$this->getCategory(),
                        'manufacturers'=>$this->getCategoryManufacturers(),
                        'shops'=>$this->getCategoryShops(),
                        'attributes'=>$this->getCategoryAttributes(),
                        'itemView'=>$this->itemView,
                        'allowedPageLimit'=>$this->allowedPageLimit,
                        'per_page'=>$this->per_page,
                        'model'=>$this->model,
                        'active'=>$activeFilters,
 
                       

        ));
    }

    /**
     * Get active/applied filters to make easier to cancel them.
     */
    public function getActiveFilters($rootcat='goods')
    {
        // Render links to cancel applied filters like prices, manufacturers, attributes.
        $menuItems = array();
                
                
                   $url = explode(';', Yii::app()->request->getQuery('url'));
                   
                   $criteria=new CDbCriteria;
                    $criteria->addInCondition('t.url', $url);
                    $criteria->order = 't.name';
                    $categ = Categories::model()->findAll($criteria);
                
                
        
                
        $manufacturers = array_filter(explode(';', Yii::app()->request->getQuery('brand')));
        $manufacturers = Brands::model()->findAllByPk($manufacturers);
        $accounts = array_filter(explode(';', Yii::app()->request->getQuery('account')));
        $accounts = User::model()->findAllByPk($accounts);
        $shops = array_filter(explode(';', Yii::app()->request->getQuery('shop')));
        $shops = Shops::model()->findAllByPk($shops);    

                if(!empty($categ))
        {
                    
            
                        
                    foreach($categ as $category)
            {
                        
                               if($category->url != 'goods' && $category->url != 'services' && $category->url != 'all'){
                               array_push($menuItems, array(
                                        'mode'=>'category',
                                        'label'=> $category->name,
                                        'url'  => Yii::app()->request->removeUrlParamCat($rootcat, $category->url),
                                        'titlecat'=>$category->url


                                   
                ));
                                }
            }
        }
                if(!empty($manufacturers))
        {
                    
            foreach($manufacturers as $manufacturer)
            {
                array_push($menuItems, array(
                                        'mode'=>'brand',
                    'label'=> $manufacturer->title,
                    'url'  => Yii::app()->request->removeUrlParam('/store/catalog/view', 'brand', $manufacturer->id)
                ));
            }
        }
        
        // Process eav attributes
        $activeAttributes = $this->getOwner()->activeAttributes;
        if(!empty($activeAttributes))
        {
            foreach($activeAttributes as $attributeName=>$value)
            {
                if(isset($this->getOwner()->eavAttributes[$attributeName]))
                {
                    $attribute = $this->getOwner()->eavAttributes[$attributeName];
                                        
                    foreach($attribute->options as $option)
                    {
                                            
                        if(isset($activeAttributes[$attribute->name]) && in_array($option->id, $activeAttributes[$attribute->name]))
                        {
                            array_push($menuItems, array(
                                                                'mode'=>'attributes',
                                                                'submode'=>$attribute->name,
                                'label'=> $option->value,
                                'url'  => Yii::app()->request->removeUrlParam('/store/catalog/view', $attribute->name, $option->id)
                            ));
                        }
                    }
                }
            }
        }
                if(Yii::app()->request->getQuery('min_price'))
        {
            array_push($menuItems, array(
                                'mode'=>'minprice',
                'label'=> Yii::t('StoreModule.core', 'от {minPrice} {c}', array('{minPrice}'=>(int)$this->getCurrentMinPrice(), '{c}'=>Yii::app()->currency->active->symbol)),
                'url'  => Yii::app()->request->removeUrlParam('/store/catalog/view', 'min_price')
            ));
        }

        if(Yii::app()->request->getQuery('max_price'))
        {
            array_push($menuItems, array(
                                'mode'=>'maxprice',
                'label'=> Yii::t('StoreModule.core', 'до {maxPrice} {c}', array('{maxPrice}'=>(int)$this->getCurrentMaxPrice(), '{c}'=>Yii::app()->currency->active->symbol)),
                'url'  => Yii::app()->request->removeUrlParam('/store/catalog/view', 'max_price')
            ));
        }

        if(!empty($accounts))
        {
                    
            foreach($accounts as $account)
            {
                array_push($menuItems, array(
                    'mode'=>'account',
                    'label'=> $account->username,
                    'url'  => Yii::app()->request->removeUrlParam('/store/catalog/view', 'account', $account->id)
                ));
            }
        } 
                if(!empty($shops)) {
                    
            foreach($shops as $shop)
            {
                array_push($menuItems, array(
                    'mode'=>'shop',
                    'label'=> $shop->title,
                    'url'  => Yii::app()->request->removeUrlParam('/store/catalog/view', 'shop', $shop->id)
                ));
            }
        }
                
        return $menuItems;
    }

    /**
     * @return array of category manufacturers
     */
    public function getCategory(){
        $items = Categories::model()->findByPk($this->setcat)->asCMenuArray();
        $data = array(
            'title'=>'Категории',
            'selectMany'=>true,
            'filters'=>array()
                        
        );

        if($items)
        {
            
             if(isset($items['items']))
            {
                $cookie = Yii::app()->request->cookies['city_id'];
                if($cookie){
                    $city_id = (int)$cookie->value;
                } else {
                    $city_id = MOSCID;
                }


                if(!empty(Yii::app()->request->cookies['city_all']) && Yii::app()->request->cookies['city_all'] == '1'){ // показываем все объявления
                    $cond =' (t.city_id !=0 or t.all_cities=1)';
                } else {
                    $cond = ' (t.city_id='.$city_id.' or t.all_cities=1)';  
                }
                
                foreach($items['items'] as $item)
                {

                        
                        $countProductsPerShop = 0;
                        $descendants = Categories::model()->findByPk($item['id'])->asCMenuArray();
                        if(isset($descendants['items']))
                        {
                            foreach($descendants['items'] as $d_item)
                            {
                                $countProductsPerShop = Products::model()
                                   ->count('(t.categories2 ='.$d_item['id'].' and t.is_active=1  and '.$cond.')');
                            }
                        }
                        $countProductsPerShop += Products::model()
                                ->count('(t.categories2 ='.$item['id'].' and t.is_active=1  and '.$cond.')');
                        $data['filters'][] = array(
                            'title'      => $item['label'],
                            'count'      => $countProductsPerShop,
                            'queryKey'   => 'catalog',
                            'queryParam' => $item['url']['url'],
                            'url_name'   => $item['url']['url'],
                            'descendants'=>$descendants

                        );

                                    
                }
                
                
            }
        }

        return $data;
    }
    /*
        public function getCategory(){
            $cat = array();
            if(is_array($this->allmodel)){

                   foreach($this->allmodel as $c){
                        $cat[] = $c->id;
                    }
                    $categories = $cat;
                    $cr = new CDbCriteria;
                    $cr->select = 't.categories2, t.name';
                    $cr->group = 't.categories2';
 
                    $cr->addCondition('t.categories2 IS NOT NULL');
            } else {
                 $categories = $this->model;
                 $cr = new CDbCriteria;
                    $cr->select = 't.categories2, t.name';
                    $cr->group = 't.categories2';
 
                    $cr->addCondition('t.categories2 IS NOT NULL');
            }
             $category = Categories::model()
                            ->active()
                            ->withcity()
                          //  ->applyCategories($categories, null)
                            ->with(array(
                                    'categories'=>array(

                                    ))
                            )
                            ->findAll($cr);
             $data = array(
            'title'=>'Категории',
            'selectMany'=>true,
            'filters'=>array()
                        
        );

        if($category)
        {
            var_dump($category);
            die();
            foreach($category as $ct)
            {

            //  $ct = $ct->categories2;
            //  if($ct)
            //  {
                                    
                    $data['filters'][] = array(
                        'name'      => $ct->name,
                        'count'      => $ct->productsShopCount,
                        'queryKey'   => 'category',
                        'queryParam' => $ct->id,
                                                'url_name'=>$ct->url
                    );
                                
            //  }
            }
        }

        return $data;
        }
        */
        /**
     * @return array of category manufacturers
     */
    public function getCategoryManufacturers()
    {

                if(!empty($this->allmodel)){

                   foreach($this->allmodel as $c){
                        $cat[] = $c->id;
                    }
                    $categories = $cat;
                } else {
                    $categories = $this->model;
                }
                    $cr = new CDbCriteria;
                    $cr->select = 't.manufacturer_id, t.name';
                    $cr->group = 't.manufacturer_id';
 
                    $cr->addCondition('t.manufacturer_id IS NOT NULL');
                    if(Yii::app()->request->getParam('account'))
                    {
                        $cr->addCondition('t.shop = 0');

                        $manufacturers = Products::model()
                            ->active()
                            ->withcity()
                          //  ->applyCategories($categories, null)
                            ->applyCategoriesWithSub($categories, null)
                            ->with(array(
                                    'manufacturer'=>array(
                                            'with'=>array(
                                                    'productsCount'=>array(
                                                            'scopes'=>array(
                                                                    'active',
                                                                    'withcity',
                                                                   // 'applyCategories'=>array($categories, null),
                                                                   // 'applyCategoriesWithSub'=>array($categories, null),
                                                                    'applyAccounts'=>array(Yii::app()->request->getParam('account')),
                                                                    'applyAttributes'=>array($this->getOwner()->activeAttributes),
                                                                    'applyMinPrice'=>array($this->convertCurrency(Yii::app()->request->getQuery('min_price'))),
                                                                    'applyMaxPrice'=>array($this->convertCurrency(Yii::app()->request->getQuery('max_price'))),

                                                            ),

                                                            )

                                            ),

                                    )))
                            ->findAll($cr);
                    } elseif(Yii::app()->request->getParam('shop'))  {
                        
                        $cr->addCondition('t.shop = 1');

                        $manufacturers = Products::model()
                            ->active()
                            ->withcity()
                            //->applyCategories($categories, null)
                            ->applyCategoriesWithSub($categories, null)
                            ->with(array(
                                    'manufacturer'=>array(
                                            'with'=>array(
                                                    'productsCount'=>array(
                                                            'scopes'=>array(
                                                                    'active',
                                                                    'withcity',
                                                                   // 'applyCategories'=>array($categories, null),
                                                                  //  'applyCategoriesWithSub'=>array($categories, null),
                                                                    'applyShops'=>array(Yii::app()->request->getParam('shop')),
                                                                    'applyAttributes'=>array($this->getOwner()->activeAttributes),
                                                                    'applyMinPrice'=>array($this->convertCurrency(Yii::app()->request->getQuery('min_price'))),
                                                                    'applyMaxPrice'=>array($this->convertCurrency(Yii::app()->request->getQuery('max_price'))),

                                                            ),

                                                            )

                                            ),

                                    )))
                            ->findAll($cr); 
                    } else {
                        $manufacturers = Products::model()
                            ->active()
                            ->withcity()
                            //->applyCategories($categories, null)
                            ->applyCategoriesWithSub($categories, null)
                            ->with(array(
                                    'manufacturer'=>array(
                                            'with'=>array(
                                                    'productsCount'=>array(
                                                            'scopes'=>array(
                                                                    'active',
                                                                    'withcity',
                                                                   // 'applyCategories'=>array($categories, null),
                                                                   // 'applyCategoriesWithSub'=>array($categories, null),
                                                                    'applyAttributes'=>array($this->getOwner()->activeAttributes),
                                                                    'applyMinPrice'=>array($this->convertCurrency(Yii::app()->request->getQuery('min_price'))),
                                                                    'applyMaxPrice'=>array($this->convertCurrency(Yii::app()->request->getQuery('max_price'))),

                                                            ),

                                                            )

                                            ),

                                    )))
                            ->findAll($cr);
                    }
 
        $data = array(
            'title'=>Yii::t('StoreModule.core', 'Бренд'),
            'selectMany'=>true,
            'filters'=>array()
                        
        );

        if($manufacturers)
        {
            foreach($manufacturers as $m)
            {
                $m = $m->manufacturer;
                if($m)
                {
                                    
                    $data['filters'][] = array(
                        'title'      => $m->title,
                        'count'      => $m->productsCount,
                        'queryKey'   => 'brand',
                        'queryParam' => $m->id,
                                                'url_name'=>$m->url
                    );
                                
                }
            }
        }

        return $data;
    }
     public function getCategoryShops()
    {
        
             
             if(!empty($this->allmodel)){

                   foreach($this->allmodel as $c){
                        $cat[] = $c->id;
                    }
                    $categories = $cat;
                } else {
                    $categories = $this->model;
                }

         $shops = null;

        if(!Yii::app()->request->getParam('account'))  { 

        $cr = new CDbCriteria;
        $cr->select = 't.user_id, t.name';
        $cr->group = 't.user_id';
               // $cr->distinct = true;
        $cr->addCondition('t.user_id IS NOT NULL and t.shop=1');
        

                if(Yii::app()->request->getParam('brand'))
                    {
                     
   
                        $shops = Products::model()
                            ->active()
                            ->withcity()
                           // ->applyCategories($categories, null)
                            ->applyCategoriesWithSub($categories, null)
                            ->with(array(
                                    'shopmag'=>array(
                                            'with'=>array(
                                                    'productsCount'=>array(
                                                            'scopes'=>array(
                                                                    'active',
                                                                    'withcity',
                                                                   // 'applyCategories'=>array($categories, null),
                                                                   // 'applyCategoriesWithSub'=>array($categories, null),
                                                                    'applyManufacturers'=>array(Yii::app()->request->getParam('brand')),
                                                                    'applyAttributes'=>array($this->getOwner()->activeAttributes),
                                                                    'applyMinPrice'=>array($this->convertCurrency(Yii::app()->request->getQuery('min_price'))),
                                                                    'applyMaxPrice'=>array($this->convertCurrency(Yii::app()->request->getQuery('max_price'))),

                                                            ),

                                                            )

                                            ),

                                    )))
                            ->findAll($cr); 
                        
                    } else {

        

             $shops = Products::model()
                ->active()
                ->withcity()
               // ->applyCategories($categories, null)
                ->applyCategoriesWithSub($categories, null)
                ->with(array(
                'shopmag'=>array(
                    'with'=>array(
                        'productsCount'=>array(
                            'scopes'=>array(
                                'active',
                                'withcity',
                               // 'applyCategories'=>array($categories, null),
                               // 'applyCategoriesWithSub'=>array($categories, null),
                                                              //  'applyManufacturers'=>array($this->manufacturers),
                                'applyAttributes'=>array($this->getOwner()->activeAttributes),
                                'applyMinPrice'=>array($this->convertCurrency(Yii::app()->request->getQuery('min_price'))),
                                'applyMaxPrice'=>array($this->convertCurrency(Yii::app()->request->getQuery('max_price'))),
                            ))
                                                        
                    ),
                                        
                )))
            ->findAll($cr);
             }
             
             }
             
                $data = array(
            'title'=>'Магазин',
            'selectMany'=>true,
            'filters'=>array()
        );

        if($shops)
        {
            foreach($shops as $m)
            {
                
                $m = $m->shopmag; 
                if($m)
                {
                    $data['filters'][] = array(
                        'title'      => $m->title,
                        'count'      => $m->productsCount,
                        'queryKey'   => 'shop',
                        'queryParam' => $m->id,
                                               // 'url_name'=>$m->url
                    );
                }
            }
        }
                
        return $data;
    }   
      
    /**
     * @return array of attributes used in category
     */
    public function getCategoryAttributes()
    {
        $data = array();

        foreach($this->attributes as $attribute)
        {
            $data[$attribute->name] = array(
                'title'      => $attribute->title,
                'selectMany' => (boolean) $attribute->select_many,
                'filters'    => array()
            );
            foreach($attribute->options as $option) // для каждого элемента (option) параметра подсчитываем кол-во товаров.
            {
                $data[$attribute->name]['filters'][] = array(
                    'title'      => $option->value,
                    'count'      => $this->countAttributeProducts($attribute, $option),
                    'queryKey'   => $attribute->name,
                    'queryParam' => $option->id,
                                        'classname'=>$option->classname

                );
            }
        }
        return $data;
    }
       
        
    /**
     * Count products by attribute and option
     * @param ProductOptions $attribute
     * @param string $option option id to search
     * @todo Optimize attributes merging
     * @return string
     */
    public function countAttributeProducts($attribute, $option)
    {
        if(!empty($this->allmodel)){

                   foreach($this->allmodel as $c){
                        $cat[] = $c->id;
                    }
                    $categories = $cat;
                    
                } else {
                    $categories = $this->model;
                }
            
                $model = new Products(null);
        $model->attachBehaviors($model->behaviors());
        $model->active()
                        ->withcity()
                      //  ->withShop($this->seller->id)
           // ->applyCategories($categories)
            ->applyCategoriesWithSub($categories)
            ->applyMinPrice($this->convertCurrency(Yii::app()->request->getQuery('min_price')))
            ->applyMaxPrice($this->convertCurrency(Yii::app()->request->getQuery('max_price')));

        if(Yii::app()->request->getParam('brand'))
            $model->applyManufacturers(explode(';', Yii::app()->request->getParam('brand')));
        if(Yii::app()->request->getParam('account')) {
            $model->applyAccounts(explode(';', Yii::app()->request->getParam('account')));
                } elseif(Yii::app()->request->getParam('shop')) {
            $model->applyShops(explode(';', Yii::app()->request->getParam('shop')));
                }
                
        $data = array($attribute->name=>$option->id);
                
        $current = $this->getOwner()->activeAttributes;

        $newData = array();
               
                
        foreach($current as $key=>$row)
        {
            if(!isset($newData[$key])) $newData[$key] = array();
            if(is_array($row))
            {
                foreach($row as $v)
                    $newData[$key][] = $v;
            }
            else
                $newData[$key][] = $row;
        }

        $newData[$attribute->name][] = $option->id;

        return $model->withEavAttributes($newData)->count();
    }

    

    /**
     * @return mixed
     */
    public function getCurrentMinPrice()
    {
        if($this->_currentMinPrice!==null)
            return $this->_currentMinPrice;

        if(Yii::app()->request->getQuery('min_price'))
            $this->_currentMinPrice=Yii::app()->request->getQuery('min_price');
        else
            $this->_currentMinPrice=Yii::app()->currency->convert($this->controller->getMinPrice());

        return $this->_currentMinPrice;
    }

    /**
     * @return mixed
     */
    public function getCurrentMaxPrice()
    {
        if($this->_currentMaxPrice!==null)
            return $this->_currentMaxPrice;

        if(Yii::app()->request->getQuery('max_price'))
            $this->_currentMaxPrice=Yii::app()->request->getQuery('max_price');
        else
            $this->_currentMaxPrice=Yii::app()->currency->convert($this->controller->getMaxPrice());

        return $this->_currentMaxPrice;
    }

    /**
     * Proxy to SCurrencyManager::activeToMain
     * @param $sum
     */
    public function convertCurrency($sum)
    {
        $cm=Yii::app()->currency;
        if($cm->active->id!=$cm->main->id)
            return $cm->activeToMain($sum);
        return $sum;
    }
        
        
        
}
