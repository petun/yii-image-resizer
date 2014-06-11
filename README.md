yii-image-resizer
=================

Yii extension for image resize.

Usage
--

- Add to components in **main.php**

```php
'components' => array(
    ...
    'resizeManager' => array(
    	'class' => 'vendor.petun.yii-image-resizer.PImageResizer'
	),
)
```

- Call methods to use resize manager. Check [phpThumb documentation] for **$params**.

```php
// simple resize
Yii::app()->resizeManager->resize($srcPath, $dstPath, $params);

// crete thumb and put in to folder with suffix
Yii::app()->resizeManager->thumb($src, $params, $suffix = '_thumb')

// params sample values
$params = array('zc' => 1, 'w' => 300, 'h' => 200); // ZOOM CROP to 300x200
$params = array('w' => 300, 'h' => 200); // Simple resize
```

- Call methods to add instagram effects. Check [phpInstagramEffects] for Example.

```php
//simple add effects
Yii::app()->resizeManager->filter($srcPath, $dstPath, $effect);

// get Effect List. In model add 
public function  getFilterList () {
        return Yii::app()->resizeManager->getFilter();
    }
```

[phpInstagramEffects]:https://github.com/zaachi/PHP-Instagram-effects
[phpThumb documentation]:http://phpthumb.sourceforge.net/demo/docs/phpthumb.readme.txt
