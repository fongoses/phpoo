<?php
Namespace Livro\Widgets\Datagrid;

use Livro\Widgets\Container\Table;
use Livro\Widgets\Base\Element;

/**
 * Representa uma Datagrid
 * @author Pablo Dall'Oglio
 */
class Datagrid extends Table
{
    private $columns;
    private $actions;
    private $rowcount;
    
    /**
     * Instancia uma nova Datagrid
     */
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Adiciona uma coluna à datagrid
     * @param $object = objeto do tipo DatagridColumn
     */
    public function addColumn(DatagridColumn $object)
    {
        $this->columns[] = $object;
    }
    
    /**
     * Adiciona uma ação à datagrid
     * @param $object = objeto do tipo DatagridAction
     */
    public function addAction(DatagridAction $object)
    {
        $this->actions[] = $object;
    }
    
    /**
     * Elimina todas linhas de dados da DataGrid
     */
    function clear()
    {
        // faz uma cópia do cabeçalho
        $copy = $this->children[0];
        
        // inicializa o vetor de linhas
        $this->children = array();
        
        // acrescenta novamente o cabeçalho
        $this->children[] = $copy;
        
        // zera a contagem de linhas
        $this->rowcount = 0;
    }
    
    /**
     * Cria a estrutura da Grid, com seu cabeçalho
     */
    public function createModel()
    {
        // adiciona uma linha à tabela
        $row = parent::addRow();
        
        // adiciona células para as ações
        if ($this->actions)
        {
            foreach ($this->actions as $action)
            {
                $celula = $row->addCell('');
            }
        }
        
        // adiciona as células para os dados
        if ($this->columns)
        {
            // percorre as colunas da listagem
            foreach ($this->columns as $column)
            {
                // obtém as propriedades da coluna
                $name = $column->getName();
                $label = $column->getLabel();
                $align = $column->getAlign();
                $width = $column->getWidth();
                
                // adiciona a célula com a coluna
                $celula = $row->addCell($label);
                $celula->align = $align;
                $celula->width = $width;
                
                // verifica se a coluna tem uma ação
                if ($column->getAction())
                {
                    $url = $column->getAction();
                    $celula->onmouseover = "this.className='tdatagrid_col_over';";
                    $celula->onmouseout  = "this.className='tdatagrid_col'";
                    $celula->onclick     = "document.location='$url'";
                }
            }
        }
    }
    
    /**
     * Adiciona um objeto na grid
     * @param $object = Objeto que contém os dados
     */
    public function addItem($object)
    {
        // adiciona uma linha na DataGrid
        $row = parent::addRow();
        
        // verifica se a listagem possui ações
        if ($this->actions)
        {
            // percorre as ações
            foreach ($this->actions as $action)
            {
                // obtém as propriedades da ação
                $url   = $action->serialize();
                $label = $action->getLabel();
                $image = $action->getImage();
                $field = $action->getField();
                
                // obtém o campo do objeto que será passado adiante
                $key    = $object->$field;
                
                // cria um link
                $link = new Element('a');
                $link->href = "{$url}&key={$key}";
                
                // verifica se o link será com imagem ou com texto
                if ($image)
                {
                    // adiciona a imagem ao link
                    $img = new Element('img');
                    $img->src="App/Images/$image";
                    $link->add($img);
                }
                else
                {
                    // adiciona o rótulo de texto ao link
                    $link->add($label);
                }
                // adiciona a célula à linha
                $row->addCell($link);
            }
        }
        
        if ($this->columns)
        {
            // percorre as colunas da DataGrid
            foreach ($this->columns as $column)
            {
                // obtém as propriedades da coluna
                $name     = $column->getName();
                $align    = $column->getAlign();
                $width    = $column->getWidth();
                $function = $column->getTransformer();
                $data     = $object->$name;
                
                // verifica se há função para transformar os dados
                if ($function)
                {
                    // aplica a função sobre os dados
                    $data = call_user_func($function, $data);
                }
                
                // adiciona a célula na linha
                $celula = $row->addCell($data);
                $celula->align = $align;
                $celula->width = $width;
            }
        }
        // incrementa o contador de linhas
        $this->rowcount ++;
    }
}