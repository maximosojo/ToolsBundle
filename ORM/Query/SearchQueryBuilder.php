<?php

/*
 * This file is part of the Maxtoan Tools package.
 * 
 * (c) https://maximosojo.github.io/tools-bundle
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maxtoan\ToolsBundle\ORM\Query;

/**
 * Query builder search
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class SearchQueryBuilder 
{
    /**
     * @var \Doctrine\ORM\QueryBuilder
     */
    private $qb;

    /**
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $criteria;

    /**
     * $alies
     * @var String
     */
    private $alies;

    /**
     * $orderBy
     * @var Array
     */
    private $orderBy;
    
    function __construct(\Doctrine\ORM\QueryBuilder $qb, \Doctrine\Common\Collections\ArrayCollection $criteria, $alies,$orderBy = null) 
    {
        $this->qb = $qb;
        $this->criteria = $criteria;
        $this->alies = $alies;
        if(is_array($orderBy)){
            $orderBy = new \Doctrine\Common\Collections\ArrayCollection($orderBy);
        }
        $this->orderBy = $orderBy;
    }

    /**
     * @return 
     */
    public function addFieldDescription() 
    {
        if(($description = $this->criteria->remove('description')) != null){
            $this->qb
                ->andWhere($this->qb->expr()->like($this->getAlies().'.description', $this->qb->expr()->literal("%$description%")));
        }

        return $this;
    }

    /**
     * @param array $fields
     * @return 
     */
    public function addFieldIn(array $fields)
    {
        foreach ($fields as $key => $field){
            $fieldToNormalize = $field;
            if(is_string($key)){
                $fieldToNormalize = $key;
            }
            $normalizeField = $this->normalizeField($this->getAlies(),$fieldToNormalize);
            $stringValue = $this->criteria->remove($field);
            if(is_array($stringValue)){
                $valueField = $stringValue;
            }else{
                $valueField = json_decode(urldecode($stringValue),false);
            }
            if(count($valueField) > 0){
                $this->qb
                    ->andWhere($this->qb->expr()->in($normalizeField, $valueField))
                    ;
                
            }
        }

        return $this;
    }

    /**
     * @param array $fields
     * @return 
     */
    public function addFieldEquals(array $fields)
    {
        foreach ($fields as $field){
            $normalizeField = $this->normalizeField($this->getAlies(),$field);
            $valueField = $this->criteria->remove($field);
            if($valueField !== null){
                $this->qb->andWhere(sprintf("%s = %s",$normalizeField,$this->qb->expr()->literal($valueField)));
            }
        }

        return $this;
    }

    /**
     * @param array $fields
     * @return 
     */
    public function addFieldLike(array $fields,$defaultValueField = null)
    {
        $orX = $this->qb->expr()->orX();
        foreach ($fields as $key => $field){
            $fieldValue = $field;
            if(is_string($key)){
                $fieldValue = $key;
            }
            $normalizeField = $this->normalizeField($this->getAlies(),$field);
            $valueField = $this->criteria->remove($fieldValue);
            if($defaultValueField !== null){
                $valueField = $defaultValueField;
            }
            if($valueField !== null){
                $orX->add($this->qb->expr()->like($normalizeField,$this->qb->expr()->literal("%".$valueField."%")));
            }
        }
        
        if($orX->count() > 0){
            $this->qb->andWhere($orX);
        }

        return $this;
    }

    /**
     * @param array $fields
     * @return 
     */
    public function addFieldTextAreaLike(array $fields,$defaultValueField = null)
    {
        $this->addFieldLike($fields,$defaultValueField);

        return $this;
    }
    
    /**
     * Añade un campo para realizar una busqueda plana por un valor
     * @param type $queryField
     * @param array $fields
     */
    public function addQueryField($queryField,array $fields) 
    {
        $valueField = $this->criteria->remove($queryField);
        if($valueField !== null){
            $this->addFieldLike($fields,$valueField);
        }

        return $this;
    }

    /**
     * @param array $fields
     * @return 
     */
    public function addFieldFromTo(array $fields)
    {
        foreach ($fields as $field){
            $fieldFrom = $field."_from";
            $fieldTo = $field."_to";
            $valueFieldFrom = $this->criteria->remove($fieldFrom);
            $valuefieldTo = $this->criteria->remove($fieldTo);
            $normalizeField = $this->normalizeField($this->getAlies(),$field);
            if($valueFieldFrom != null){
                $this->qb
                    ->andWhere(sprintf("%s >= :%s",$normalizeField,$fieldFrom))
                    ->setParameter($fieldFrom,$valueFieldFrom)
                    ;
            }
            if($valuefieldTo != null){
                $this->qb
                    ->andWhere(sprintf("%s <= :%s",$normalizeField,$fieldTo))
                    ->setParameter($fieldTo,$valuefieldTo)
                    ;
            }
        }

        return $this;
    }

    /**
     * Añade busquedas de fechas desde, hasta
     * @param array $fieldDates
     */
    public function addFieldDateFromTo(array $fieldDates) 
    {
        $formatDate = "Y-m-d";
        $completeDateFrom = " 00:00:00";
        $completeDateTo = " 23:59:59";

        foreach ($fieldDates as $fieldDate) {
            $fieldDateNormalize = $this->normalizeField($this->getAlies(), $fieldDate);
            $fieldDateDayFrom = $this->criteria->remove("day_from_".$fieldDate);
            $fieldDateMonthFrom = $this->criteria->remove("month_from_".$fieldDate);
            $fieldDateYearFrom = $this->criteria->remove("year_from_".$fieldDate);
            
            $fieldDateDayTo = $this->criteria->remove("day_to_".$fieldDate);
            $fieldDateMonthTo = $this->criteria->remove("month_to_".$fieldDate);
            $fieldDateYearTo = $this->criteria->remove("year_to_".$fieldDate);
            
            $dateTimeFrom = null;
            $dateTimeTo = null;
            if($fieldDateDayFrom !== null && $fieldDateMonthFrom !== null && $fieldDateYearFrom !== null){
                $fieldDateDayFrom = str_pad($fieldDateDayFrom, 2,"0",STR_PAD_LEFT);
                $fieldDateMonthFrom = str_pad($fieldDateMonthFrom, 2,"0",STR_PAD_LEFT);
                $fieldDateValue = sprintf("%s/%s/%s",$fieldDateDayFrom,$fieldDateMonthFrom,$fieldDateYearFrom);
                $dateTimeFrom = \DateTime::createFromFormat("d/m/Y", $fieldDateValue);
            }
            
            if($fieldDateDayTo !== null && $fieldDateMonthTo !== null && $fieldDateYearTo !== null){
                $fieldDateDayTo = str_pad($fieldDateDayTo, 2,"0",STR_PAD_LEFT);
                $fieldDateMonthTo = str_pad($fieldDateMonthTo, 2,"0",STR_PAD_LEFT);
                $fieldDateValue = sprintf("%s/%s/%s",$fieldDateDayTo,$fieldDateMonthTo,$fieldDateYearTo);
                $dateTimeTo = \DateTime::createFromFormat("d/m/Y", $fieldDateValue);
            }

            $addFieldDateYear = function($condition,$fieldValue) use ($fieldDateNormalize){
                $this->qb->andWhere(sprintf("YEAR(%s) %s %s",$fieldDateNormalize,$condition,$fieldValue));
            };
            $addFieldDateMonth = function($condition,$fieldValue) use ($fieldDateNormalize){
                $this->qb->andWhere(sprintf("MONTH(%s) %s %s",$fieldDateNormalize,$condition,$fieldValue));
            };
            $addFieldDateDay = function($condition,$fieldValue) use ($fieldDateNormalize){
                $this->qb->andWhere(sprintf("DAY(%s) %s %s",$fieldDateNormalize,$condition,$fieldValue));
            };
            
            if($dateTimeFrom !== null && $dateTimeTo !== null){
                $this->qb->andWhere($this->qb->expr()->between($fieldDateNormalize,
                        $this->qb->expr()->literal($dateTimeFrom->format($formatDate.$completeDateFrom)), 
                        $this->qb->expr()->literal($dateTimeTo->format($formatDate.$completeDateTo))));
            }else{
                
                if($dateTimeFrom !== null){
                    $this->qb->andWhere($this->qb->expr()->gte(
                            $fieldDateNormalize,$this->qb->expr()->literal($dateTimeFrom->format($formatDate.$completeDateFrom))));
                }else{
                    if($fieldDateYearFrom !== null){
                        $addFieldDateYear(">=",$fieldDateYearFrom);
                    }
                    if($fieldDateMonthFrom !== null){
                        $addFieldDateMonth(">=",$fieldDateMonthFrom);
                    }
                    if($fieldDateDayFrom !== null){
                        $addFieldDateDay(">=",$fieldDateDayFrom);
                    }
                }
                if($dateTimeTo !== null){
                    $this->qb->andWhere(
                            $this->qb->expr()->lte($fieldDateNormalize,
                                    $this->qb->expr()->literal($dateTimeTo->format($formatDate.$completeDateTo))));
                }else{
                    if($fieldDateYearTo !== null){
                        $addFieldDateYear("<=",$fieldDateYearTo);
                    }
                    if($fieldDateMonthTo !== null){
                        $addFieldDateMonth("<=",$fieldDateMonthTo);
                    }
                    if($fieldDateDayTo !== null){
                        $addFieldDateDay("<=",$fieldDateDayTo);
                    }
                }
            }
        }

        return $this;
    }

    /**
     * @param array $fieldDates
     * @return 
     */
    public function addFieldDate(array $fieldDates) 
    {
        foreach ($fieldDates as $fieldDate) {
            $fieldDateDay = $this->criteria->remove("day_".$fieldDate);
            $fieldDateMonth = $this->criteria->remove("month_".$fieldDate);
            $fieldDateYear = $this->criteria->remove("year_".$fieldDate);
            if(empty($fieldDateDay) && empty($fieldDateMonth) && empty($fieldDateYear) ){
                continue;
            }
            
            if($fieldDateDay !== null && $fieldDateMonth !== null && $fieldDateYear !== null){
                $fieldDateDay = str_pad($fieldDateDay, 2,"0",STR_PAD_LEFT);
                $fieldDateMonth = str_pad($fieldDateMonth, 2,"0",STR_PAD_LEFT);
                
                $fieldDateValue = sprintf("%s/%s/%s",$fieldDateDay,$fieldDateMonth,$fieldDateYear);
                $dateTime = \DateTime::createFromFormat("d/m/Y", $fieldDateValue);
                $this->qb->andWhere($this->qb->expr()->like($this->getAlies().".".$fieldDate,$this->qb->expr()->literal("%".$dateTime->format("Y-m-d")."%")));
            }else{                
                if($fieldDateDay !== null){
                    $fieldDateDay = str_pad($fieldDateDay, 2,"0",STR_PAD_LEFT);
                    $this->qb
                        ->andWhere(
                            $this->qb->expr()->like(
                                $this->getAlies().".".$fieldDate,$this->qb->expr()->literal("%-%-".$fieldDateDay)
                                             )
                                );
                }
                if($fieldDateMonth !== null){
                    $fieldDateMonth = str_pad($fieldDateMonth, 2,"0",STR_PAD_LEFT);
                    $this->qb
                        ->andWhere(
                            $this->qb->expr()->like(
                                $this->getAlies().".".$fieldDate,$this->qb->expr()->literal("%-".$fieldDateMonth."-%")
                                             )
                                );
                }
                if($fieldDateYear !== null){
                    $this->qb
                        ->andWhere(
                            $this->qb->expr()->like(
                                $this->getAlies().".".$fieldDate,$this->qb->expr()->literal($fieldDateYear."-%-%")
                                             )
                                );
                }
            }
        }

        return $this;
    }

    /**
     * @return 
     */
    public function addFieldMaxResults() 
    {
        if(($maxResults = $this->criteria->remove('max_results')) != null && $maxResults > 0){
            $this->qb->setMaxResults($maxResults);
        }

        return $this;
    }
    
    /**
     * Sorting apply
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param  array
     * @param  array
     * @return [type]
     */
    public function applySorting(array $fields,array $default = [])
    {
        if($this->orderBy === null){
            return;
        }

        $orderByCount = $this->orderBy->count();
        foreach ($this->orderBy as $field => $value) {
            if(!in_array($field, $fields)){
                continue;
            }
            $valueFiled = strtoupper($value);
            if($valueFiled === null){
                continue;
            }
            if($valueFiled !== "DESC" && $valueFiled !== "ASC"){
                continue;
            }
            $fieldNormalize = $this->normalizeField($this->getAlies(), $field);
            $this->qb->addOrderBy($fieldNormalize, $valueFiled);
        }

        if($orderByCount == 0 && count($default) > 0){
            foreach ($default as $field => $order) {
                $fieldNormalize = $this->normalizeField($this->getAlies(), $field);
                $this->qb->addOrderBy($fieldNormalize, $order);
            }
        }
    }
    
    /**
     * Normalize
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param  string
     * @param  string
     * @return string response
     */
    private function normalizeField($alies,$field) 
    {
        $fieldResponse = "";
        $fieldExplode = explode("__", $field);
        if(count($fieldExplode) == 2){
            $fieldResponse = sprintf("%s_%s.%s",$alies,$fieldExplode[0],$fieldExplode[1]);
        }else{
            $fieldResponse = sprintf("%s.".$fieldExplode[0],$alies);
        }
        
        return $fieldResponse;
    }

    /**
     * Alies repository
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @return string
     */
    private function getAlies()
    {
        return $this->alies;
    }
}
