<?php

Yii::import('zii.widgets.grid.CDataColumn');
class EDataColumnExt extends CDataColumn {

        public function renderFilterCellContent() {

                if($this->filter!==false && $this->grid->filter!==null && $this->name!==null && is_int(strpos($this->name,'.')))
                {
                        if(isset($this->filter['type'])) {
                                $type = $this->filter['type'];
                                unset($this->filter['type']);
                                $attributes=$this->filter;
//                              $attributes['model']=$this->getParent()->getModel();
//                              $attributes['attribute']=$this->name;
                                ob_start();
                                $this->grid->owner->widget($type, $attributes);
                                echo ob_get_clean();
                        }
//                      else
//                              echo $this->filter;
                }
                else
                        parent::renderFilterCellContent();

        }

}

?>