<?php

declare(strict_types = 1);

namespace Statistics\Calculator;

use Statistics\Dto\StatisticsTo;
use Statistics\Dto\ParamsTo;
use SocialPost\Dto\SocialPostTo;
use PHPUnit\Framework\TestCase;
use DateTime;

/**
 * Class ATestTest
 *
 * @package Tests\unit
 */
class NoopCalculatorTest extends TestCase
{
    /**
     * @test
     */
    public function testNoop(): void
    {
        $startDate = DateTime::createFromFormat('m, Y', "9, 22");;
        $endDate = DateTime::createFromFormat('m, Y', "10, 22");;
        $calculator = new CalculatorComposite();
        $noopcalculator = new NoopCalculator();
        $paramsTo = (new ParamsTo())
                ->setStatName("Test")
                ->setStartDate($startDate)
                ->setEndDate($endDate);
        $noopcalculator->setParameters($paramsTo);
        $calculator->addChild($noopcalculator);
        $result = $calculator->calculate();
        $children = $result->getChildren();
        $this->assertSame($children[0]->getValue(),0.0); // check empty
        $dto = (new SocialPostTo())
            ->setId("1")
            ->setAuthorName("test1")
            ->setAuthorId("1")
            ->setText("test")
            ->setType("status")
            ->setDate(DateTime::createFromFormat('m, Y', "9, 22"));
        $calculator->accumulateData($dto);
        $result = $calculator->calculate();
        $children = $result->getChildren();
        $this->assertSame($children[0]->getValue(),1.0); // one post
        $dto = (new SocialPostTo())
            ->setId("2")
            ->setAuthorName("test1")
            ->setAuthorId("1")
            ->setText("test")
            ->setType("status")
            ->setDate(DateTime::createFromFormat('m, Y', "9, 22"));
        $calculator->accumulateData($dto);
        $result = $calculator->calculate();
        $children = $result->getChildren();
        $this->assertSame($children[0]->getValue(),2.0); // two posts one author
        $dto = (new SocialPostTo())
            ->setId("3")
            ->setAuthorName("test2")
            ->setAuthorId("2")
            ->setText("test")
            ->setType("status")
            ->setDate(DateTime::createFromFormat('m, Y', "9, 22"));
        $calculator->accumulateData($dto);
        $result = $calculator->calculate();
        $children = $result->getChildren();
        $this->assertSame($children[0]->getValue(),1.5); // three posts two authors
    }
}
