<?php


Yii::import('zii.widgets.grid.CButtonColumn');

/**
 *## Bootstrap button column widget.
 *
 * Used to set buttons to use Glyphicons instead of the defaults images.
 *
 * @package booster.widgets.grids.columns
 */
class MyButtonColumn extends CButtonColumn
{
	/**
	 * @var string the view button icon (defaults to 'btn btn-mini btn-success').
	 */
	public $viewButtonClass = 'btn btn-xs btn-outline btn-default add-tooltip';
	/**
	 * @var string the view button icon (defaults to 'btn btn-mini btn-success').
	 */
	public $viewInButtonClass = 'btn btn-xs btn-outline btn-info add-tooltip';
	/**
	 * @var string the update button icon (defaults to 'btn btn-mini btn-info').
	 */
	public $updateButtonClass = 'btn btn-xs btn-outline btn-success add-tooltip';

	/**
	 * @var string the delete button class (defaults to 'btn btn-mini btn-danger').
	 */
	public $deleteButtonClass = 'btn btn-xs btn-outline btn-danger add-tooltip';
	/**
	 * @var string the view button icon (defaults to 'eye-open').
	 */
	public $viewButtonIcon = 'eye-open';
	/**
	 * @var string the view button icon (defaults to 'eye-open').
	 */
	public $viewInButtonIcon = 'eye-open';

	/**
	 * @var string the update button icon (defaults to 'pencil').
	 */
	public $updateButtonIcon = 'pencil';

	/**
	 * @var string the delete button icon (defaults to 'trash').
	 */
	public $deleteButtonIcon = 'times';

	/**
	 *### .initDefaultButtons()
	 *
	 * Initializes the default buttons (view, update and delete).
	 */
	protected function initDefaultButtons()
	{
		parent::initDefaultButtons();

		if ($this->viewButtonIcon !== false && !isset($this->buttons['view']['icon'])) {
			$this->buttons['view']['icon'] = $this->viewButtonIcon;
		}
		if ($this->viewInButtonIcon !== false && !isset($this->buttons['viewin']['icon'])) {
			$this->buttons['viewin']['icon'] = $this->viewInButtonIcon;
		}
		if ($this->updateButtonIcon !== false && !isset($this->buttons['update']['icon'])) {
			$this->buttons['update']['icon'] = $this->updateButtonIcon;
		}
		if ($this->deleteButtonIcon !== false && !isset($this->buttons['delete']['icon'])) {
			$this->buttons['delete']['icon'] = $this->deleteButtonIcon;
		}

		$this->buttons['view']['options']['class'] = $this->viewButtonClass;
		$this->buttons['viewin']['options']['class'] = $this->viewInButtonClass;
		$this->buttons['update']['options']['class'] = $this->updateButtonClass;
		$this->buttons['delete']['options']['class'] = $this->deleteButtonClass;

		/*if (!empty($this->buttons['view']['icon']))
			$this->buttons['view']['icon'] .= ' white';
		if (!empty($this->buttons['update']['icon']))
			$this->buttons['update']['icon'] .= ' white';
		if (!empty($this->buttons['delete']['icon']))
			$this->buttons['delete']['icon'] .= ' white';
		*/
	}

	/**
	 *### .renderButton()
	 *
	 * Renders a link button.
	 *
	 * @param string $id the ID of the button
	 * @param array $button the button configuration which may contain 'label', 'url', 'imageUrl' and 'options' elements.
	 * @param integer $row the row number (zero-based)
	 * @param mixed $data the data object associated with the row
	 */
	protected function renderButton($id, $button, $row, $data)
	{
		if (isset($button['visible']) && !$this->evaluateExpression($button['visible'], array('row'=>$row, 'data'=>$data)))
			return;

		$label = isset($button['label']) ? $button['label'] : $id;
		$url = isset($button['url']) ? $this->evaluateExpression($button['url'], array('data'=>$data, 'row'=>$row)) : '#';
		$options = isset($button['options']) ? $button['options'] : array();

		if (isset($button['param']))
			$options['param'] = $this->evaluateExpression($button['param'], array('data'=>$data, 'row'=>$row));

		/* added to render additional html attribute */
        if (isset($button['options']) AND !(empty($button['options']))) {
            foreach ($button['options'] as $key => $value) {
                if (preg_match('#\$(data|row)#', $value)) {
                    $options["$key"] = $this->evaluateExpression($button['options'][$key], array('data' => $data, 'row' => $row));
                } else {
                    $options["$key"] = $value;
                }
            }
        }

        /* end */

		//if (!isset($options['title']))
		//	$options['title'] = $label;

		if (!isset($options['class']))
			$options['class'] = 'btn btn-mini';

		//if (!isset($options['rel']))
		//	$options['rel'] = 'tooltip';

		if (!isset($options['data-original-title']))
			$options['data-original-title'] = $label;

		if (isset($button['icon']))
		{

			if (strpos($button['icon'], 'icon') === false){
				$button['icon'] = 'fa fa-'.implode(' fa-', explode(' ', $button['icon']));
			}

			echo CHtml::link('<i class="'.$button['icon'].'"></i>', $url, $options);
		}
		else if (isset($button['imageUrl']) && is_string($button['imageUrl'])){
			echo CHtml::link(CHtml::image($button['imageUrl'], $label), $url, $options);
		}
		else{
			echo CHtml::link($label, $url, $options);
		}
	}
}
