<?php
// src/Twig/AppExtension.php
namespace App\Twig;

use App\Entity\Product;
use App\Entity\ProductAttribute;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\String\UnicodeString;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use function mysql_xdevapi\getSession;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('dimensionsCheck', [$this, 'dimensionsCheck']),
        ];
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('dimensionsFormat', [$this, 'dimensionsFormat']),
            new TwigFunction('getsessionidproduct', [$this, 'getSessionIdProduct']),
        ];
    }

    public function dimensionsFormat(?ProductAttribute $productAttribute): string
    {
        $width = $productAttribute->getWidth() ?? '?';
        $length = $productAttribute->getLength() ?? '?';
        $height = $productAttribute->getHeight() ?? '?';
        return $width.'x'.$length.'x'.$height;
    }
    public function dimensionsCheck(?int $dimension): string
    {
        return match ($dimension){
            Product::DIMENSIONS_SMALL => 'Small',
            Product::DIMENSIONS_MEDIUM => 'Medium',
            Product::DIMENSIONS_LARGE => 'Large',
            default => ''
        };
    }
    public function getSessionIdProduct(): ?string
    {
        $request = new RequestStack();
        $session = $request->getSession();
        return $session->get('idProduct') ?? null;
    }
}