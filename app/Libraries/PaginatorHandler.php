<?php

namespace App\Libraries;

class PaginatorHandler
{

    /**
     * Numero de pagina actual
     * @var int
     */
    protected int $pageNumber = 1;

    /**
     * A partir de donde comienza fetchAll a buscar resultados
     * @var int
     */
    protected int $offset = 0;

    /**
     * Numero total de registros
     * @var int
     */
    protected int $totalRows = 0;

    /**
     * Numero total de paginas
     * @var int
     */
    protected int $totalPages = 0;

    /**
     * Numero de registros por pagina que se van a mostrar
     */
    CONST ROWS_PER_PAGE = 10;

    public function __construct(int $pageNumber, array $data) {

        $this->setPageNumber($pageNumber);
        $this->setOffset($pageNumber);
        $this->setTotalRows($data);
        $this->setTotalPages();

    }

    /**
     * Get numero de pagina actual
     *
     * @return  int
     */ 
    public function getPageNumber()
    {
        return $this->pageNumber;
    }

    /**
     * Set numero de pagina actual
     *
     * @param  int  $pageNumber  Numero de pagina actual
     *
     * @return  self
     */ 
    public function setPageNumber(int $pageNumber)
    {
        $this->pageNumber = $pageNumber;

        return $this;
    }

    /**
     * Get a partir de donde comienza fetchAll a buscar resultados
     *
     * @return  int
     */ 
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * Set a partir de donde comienza fetchAll a buscar resultados
     *
     * @param  int  $pageNumber numero de pagina actual
     *
     * @return  self
     */ 
    public function setOffset(int $pageNumber)
    {
        $this->offset = ($pageNumber  -1) * SELF::ROWS_PER_PAGE;

        return $this;
    }

    /**
     * Get numero total de registros
     *
     * @return  int
     */ 
    public function getTotalRows()
    {
        return $this->totalRows;
    }

    /**
     * Set numero total de registros
     *
     * @param  array  $data                 los registros que se encontraron
     *
     * @return  self
     */ 
    public function setTotalRows(array $data)
    {
        $this->totalRows = count($data);

        return $this;
    }

    /**
     * Get numero total de paginas
     *
     * @return  int
     */ 
    public function getTotalPages()
    {
        return $this->totalPages;
    }

    /**
     * Set numero total de paginas
     *
     * @return  self
     */ 
    public function setTotalPages()
    {
        $this->totalPages = ceil($this->totalRows / SELF::ROWS_PER_PAGE);

        return $this;
    }

    /**
     * Devuelve un array con las propiedades
     * 
     * @return array
     */
    public function getPropertiesToArray(){

        return [
            'pageNumber'    => $this->getPageNumber(),
            'offset'        => $this->getOffset(),
            'totalRows'     => $this->getTotalRows(),
            'totalPages'    => $this->getTotalPages(),
            'rowsPerPage'   => SELF::ROWS_PER_PAGE
        ];

    }
}
