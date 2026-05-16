import { useEffect, useState } from 'react';
import { useParams, Link } from 'react-router-dom';
import api from '../api/axios';
import { useAuth } from '../context/AuthContext';
import ProductCard from '../components/ProductCard';
import { formatPrice, getProductImage, getEffectivePrice } from '../utils/format';

export default function ProductDetail() {
  const { slug } = useParams();
  const { user } = useAuth();
  const [data, setData] = useState(null);
  const [qty, setQty] = useState(1);
  const [variantId, setVariantId] = useState(null);
  const [msg, setMsg] = useState('');

  useEffect(() => {
    api.get(`/products/${slug}`).then((res) => setData(res.data)).catch(console.error);
  }, [slug]);

  const addToCart = async () => {
    if (!user) { window.location.href = '/login'; return; }
    try {
      await api.post('/cart', { product_id: data.product.id, product_variant_id: variantId, quantity: qty });
      setMsg('Added to cart!');
    } catch (e) { setMsg(e.response?.data?.message || 'Failed to add'); }
  };

  const addToWishlist = async () => {
    if (!user) { window.location.href = '/login'; return; }
    await api.post('/wishlist', { product_id: data.product.id });
    setMsg('Added to wishlist!');
  };

  if (!data) return <div className="flex justify-center py-20 text-gray-400">Loading...</div>;

  const { product, related } = data;
  const price = getEffectivePrice(product);
  const avgRating = product.reviews?.length
    ? (product.reviews.reduce((s, r) => s + r.rating, 0) / product.reviews.length).toFixed(1)
    : null;

  return (
    <div className="max-w-7xl mx-auto px-4 py-8">
      <div className="grid md:grid-cols-2 gap-8 mb-12">
        <img src={getProductImage(product)} alt={product.name} className="rounded-xl w-full aspect-square object-cover" />
        <div>
          <p className="text-brand-600 text-sm font-medium">{product.brand?.name}</p>
          <h1 className="text-3xl font-bold mt-1 mb-2">{product.name}</h1>
          {avgRating && <p className="text-yellow-500 mb-4">★ {avgRating} ({product.reviews.length} reviews)</p>}
          <div className="flex items-center gap-3 mb-4">
            <span className="text-3xl font-bold text-brand-600">{formatPrice(price)}</span>
            {product.discount_price && (
              <span className="text-xl text-gray-400 line-through">{formatPrice(product.regular_price)}</span>
            )}
          </div>
          <p className="text-gray-600 mb-6">{product.description}</p>

          {product.variants?.length > 0 && (
            <div className="mb-4">
              <label className="text-sm font-medium block mb-2">Select Option</label>
              <div className="flex flex-wrap gap-2">
                {product.variants.map((v) => (
                  <button
                    key={v.id}
                    onClick={() => setVariantId(v.id)}
                    className={`px-4 py-2 border rounded-lg text-sm ${variantId === v.id ? 'border-brand-600 bg-brand-50 text-brand-600' : 'border-gray-300'}`}
                  >
                    {v.value}
                  </button>
                ))}
              </div>
            </div>
          )}

          <div className="flex items-center gap-4 mb-6">
            <div className="flex items-center border rounded-lg">
              <button onClick={() => setQty(Math.max(1, qty - 1))} className="px-4 py-2">−</button>
              <span className="px-4 py-2 font-medium">{qty}</span>
              <button onClick={() => setQty(qty + 1)} className="px-4 py-2">+</button>
            </div>
            <span className="text-sm text-gray-500">{product.stock} in stock</span>
          </div>

          <div className="flex gap-3">
            <button onClick={addToCart} className="btn-primary flex-1">Add to Cart</button>
            <button onClick={addToWishlist} className="btn-outline">♥ Wishlist</button>
          </div>
          {msg && <p className="mt-3 text-sm text-green-600">{msg}</p>}
        </div>
      </div>

      {product.reviews?.length > 0 && (
        <section className="mb-12">
          <h2 className="text-xl font-bold mb-4">Customer Reviews</h2>
          <div className="space-y-4">
            {product.reviews.map((r) => (
              <div key={r.id} className="card p-4">
                <div className="flex items-center gap-2 mb-1">
                  <span className="font-medium">{r.user?.name}</span>
                  <span className="text-yellow-500">{'★'.repeat(r.rating)}</span>
                </div>
                <p className="text-gray-600 text-sm">{r.comment}</p>
              </div>
            ))}
          </div>
        </section>
      )}

      {related?.length > 0 && (
        <section>
          <h2 className="text-xl font-bold mb-4">Related Products</h2>
          <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
            {related.map((p) => <ProductCard key={p.id} product={p} />)}
          </div>
        </section>
      )}
    </div>
  );
}
