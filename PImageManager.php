<?

require_once(dirname(__FILE__).'/thumb/phpthumb.class.php');

class PImageManager extends CApplicationComponent {
    
    // конфа по 
    public $thumbs;

    // папка для записи
    public $basePath;

    // УРЛ для показа
    public $baseUrl;

    // сюда попадает результат при сохранении изображения
    private $_rModel;
    
    
    public function init() {
        $this->basePath = Yii::app()->basePath . $this->basePath;
        $this->baseUrl = Yii::app()->baseUrl . $this->baseUrl;


        Yii::trace('Setup complite. Paths is '.$this->basePath . '::' . $this->baseUrl);
        // void )) -- все параметры через конфу
    }
    
    public function saveImage(CUploadedFile $uploadFile,$savePath,array $resizeParams = array()) { 
        $path = $this->initDir($savePath,true);
        
        if (!empty($path) && is_object($uploadFile)) {
            
            $fileName = md5(time() . uniqid() ) . '.' .  $uploadFile->getExtensionName();
            $fullSavePath = $path . $fileName;
            
            if ($uploadFile->saveAs($fullSavePath)) {
                Yii::trace('File uploaded success to '.$fullSavePath);

                if (!empty($resizeParams)) {
                    foreach ($resizeParams as $resizePropName) {
                        if (array_key_exists($resizePropName, $this->thumbs)) {
                            $this->resize($fullSavePath, $path.'thumbs/'.$resizePropName.'_'.$fileName , $this->thumbs[$resizePropName] );
                        }
                    }
                }

                return  $savePath . $fileName;
                /*$image = new Image;
                $image->name = $uploadFile->name;
                $image->type = $uploadFile->type;
                $image->size = $uploadFile->size;
                $image->path = $save_path . $file_name;
                if ($image->save()) {
                    $model = $image;
                    Yii::trace('Save Image success.. ID: '.$image->id);
                    return $image->id;
                }*/
            }
        }
        

        Yii::trace('ImgManager errror while save image');
        return false;
    }

    // возращаем модель, которая была при сохранеии.. (!!! сейчас закомментирована которая)
    public function getResultModel() {
        return $this->_rModel;
    }

    public function delete($file) {
        $f = $this->basePath . $file;
        
        if (file_exists($f) && is_file($f))
            return unlink($f);
        return false;
    }

    public function getParamImage($param,$file) {
        $dir = dirname($file);
        $file = basename($file);

        $target = $dir . '/thumbs/' . $param . '_'. $file;

        return $this->baseUrl . $target;
    }
    
    protected function initDir($path,$includeThumbDir = true) {
        $path = $this->basePath . $path;
        
        $this->mkDir($path);
        if ($includeThumbDir) {
            $this->mkDir($path.'thumbs/');
        }
        
        return $path;
    }
    
    
    protected function mkDir($dir) {
        if (!file_exists($dir)) {
            return mkdir($dir,0777,true);
        }
        return is_dir($dir);
    }


    protected function resize($src,$dst,$params) { 
        $phpThumb = new phpThumb();
        
        $phpThumb->setParameter('config_output_format', 'jpeg');
        $phpThumb->setParameter('config_allow_src_above_docroot',true);
        $phpThumb->setParameter('q', 95);

        $phpThumb->setSourceFilename($src ); 
        foreach ($params as $key=>$val) {
            $phpThumb->setParameter($key, $val);    
        }


        if ($phpThumb->GenerateThumbnail()) { 
            return $phpThumb->RenderToFile($dst);
        }

        return false;
    }
}