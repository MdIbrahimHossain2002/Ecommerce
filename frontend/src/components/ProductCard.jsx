import { Link } from 'react-router-dom';
import { formatPrice, getProductImage, getEffectivePrice } from '../utils/format';

export default function ProductCard({ product }) {
  const price = getEffectivePrice(product);
  const hasDiscount = product.discount_price && product.discount_price < product.regular_price;

  return (
    <Link to={`/products/${product.slug}`} className="card group overflow-hidden hover:shadow-md transition-shadow">
      <div className="aspect-square overflow-hidden bg-gray-100 relative">
        <img
          src={getProductImage(product)}
          alt={product.name}
          className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
        />
        {hasDiscount && (
          <span className="absolute top-2 left-2 bg-red-500 text-white text-xs font-semibold px-2 py-1 rounded">
            SALE
          </span>
        )}
        {product.is_new_arrival && (
          <span className="absolute top-2 right-2 bg-green-500 text-white text-xs font-semibold px-2 py-1 rounded">
            NEW
          </span>
        )}
      </div>
      <div className="p-4">
        <p className="text-xs text-gray-500 mb-1">{product.brand?.name || product.category?.name}</p>
        <h3 className="font-medium text-gray-900 line-clamp-2 mb-2">{product.name}</h3>
        <div className="flex items-center gap-2">
          <span className="text-lg font-bold text-brand-600">{formatPrice(price)}</span>
          {hasDiscount && (
            <span className="text-sm text-gray-400 line-through">{formatPrice(product.regular_price)}</span>
          )}
        </div>
      </div>
    </Link>
  );
}

