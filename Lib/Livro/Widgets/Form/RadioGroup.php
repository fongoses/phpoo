<?php
Namespace Livro\Widgets\Form;

/**
 * Representa um grupo de Radio Buttons
 * @author Pablo Dall'Oglio
 */
class RadioGroup extends Field implements FormElementInterface
{
    private $layout = 'vertical';
    private $items;
    
    /**
     * Define a direção das opções (vertical ou horizontal)
     */
    public function setLayout($dir)
    {
        $this->layout = $dir;
    }
    
    /**
     * Adiciona itens (botões de rádio)
     * @param $items = array indexado contendo os itens
     */
    public function addItems($items)
    {
        $this->items = $items;
    }
    
    /**
     * Exibe o widget na tela
     */
    public function show()
    {
        if ($this->items)
        {
            // percorre cada uma das opções do rádio
            foreach ($this->items as $index => $label)
            {
                $button = new RadioButton($this->name);
                $button->setValue($index);
                // se possui qualquer valor
                if ($this->value == $index)
                {
                    // marca o radio button
                    $button->setProperty('checked', '1');
                }
                $button->show();
                $obj = new Label($label);
                $obj->show();
                if ($this->layout == 'vertical')
                {
                    // exibe uma tag de quebra de linha
                    $br = new Element('br');
                    $br->show();
                }
                echo "\n";
            }
        }
    }
}