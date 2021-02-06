<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use App\Models\Category;
use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CategoryTest extends TestCase
{

    private $category;

    protected function  setUp(): void
    {
        parent::setUp();
        $this->category = new Category();
    }

    public function testIfUseTraits()
    {
        $traits = [ SoftDeletes::class, Uuid::class, HasFactory::class ];
        $categoryTraits = array_keys(class_uses(Category::class));
        $this->assertEqualsCanonicalizing($traits, $categoryTraits);
    }

    public function testCast()
    {
        $casts = ['id' => 'string', 'is_active' => 'boolean', 'deleted_at' => 'datetime'];
        $this->assertEquals($casts, $this->category->getCasts());
    }

    public function testIfIncrementingIsFalse()
    {
        $this->assertFalse($this->category->incrementing);
    }

    public function testFillableAttributes()
    {
        $fillable = [ 'name', 'description', 'is_active' ];
        $this->assertEquals($fillable, $this->category->getFillable());
    }

    public function testDatesAttibutes()
    {
        $dates = ['deleted_at','created_at','updated_at'];
        $this->assertEqualsCanonicalizing($dates, $this->category->getDates());
    }
}
