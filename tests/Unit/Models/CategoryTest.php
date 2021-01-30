<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use App\Models\Category;
use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CategoryTest extends TestCase
{
    public function testFillableAttributes()
    {
        $fillable = [ 'name', 'description', 'is_active' ];
        $category = new Category();
        $this->assertEquals($fillable, $category->getFillable());
    }

    public function testIfUseTraits(){
        $traits = [ SoftDeletes::class, Uuid::class, HasFactory::class ];
        $categoryTraits = array_keys(class_uses(Category::class));
        $this->assertEqualsCanonicalizing($traits, $categoryTraits);
    }

    public function testCast()
    {
        $casts = ['id' => 'string', 'is_active' => 'boolean', 'deleted_at' => 'datetime'];
        $category = new Category();
        $this->assertEquals($casts, $category->getCasts());
    }

    public function testIfIncrementingIsFalse()
    {
        $category = new Category();
        $this->assertFalse($category->incrementing);
    }

    public function testDatesAttibutes()
    {
        $dates = ['deleted_at','created_at','updated_at'];
        $category = new Category();
        $this->assertEqualsCanonicalizing($dates, $category->getDates());
    }
}
