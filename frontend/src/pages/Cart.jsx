import { useEffect, useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import api from '../api/axios';
import { formatPrice, getProductImage } from '../utils/format';

export default function Cart() {
  const [cart, setCart] = useState(null);
  const [coupon, setCoupon] = useState('');
  const navigate = useNavigate();

  const load = () => api.get('/cart').then((r) => setCart(r.data)).catch(() => navigate('/login'));

  useEffect(() => { load(); }, []);

  const updateQty = async (id, quantity) => {
    if (quantity < 1) return;
    await api.put(`/cart/${id}`, { quantity });
    load();
  };

  const remove = async (id) => {
    await api.delete(`/cart/${id}`);
    load();
  };

  const applyCoupon = async () => {
    await api.post('/cart/coupon', { code: coupon });
    load();
  };

  if (!cart) return <div className="text-center py-20">Loading cart...</div>;

  return (
    <div className="max-w-7xl mx-auto px-4 py-8">
      <h1 className="text-3xl font-bold mb-6">Shopping Cart</h1>
      {!cart.items?.length ? (
        <div className="text-center py-16">
          <p className="text-gray-500 mb-4">Your cart is empty</p>
          <Link to="/products" className="btn-primary">Continue Shopping</Link>
        </div>
      ) : (
        <div className="grid md:grid-cols-3 gap-8">
          <div className="md:col-span-2 space-y-4">
            {cart.items.map((item) => (
              <div key={item.id} className="card p-4 flex gap-4">
                <img src={getProductImage(item.product)} alt="" className="w-24 h-24 object-cover rounded-lg" />
                <div className="flex-1">
                  <h3 className="font-medium">{item.product?.name}</h3>
                  {item.variant && <p className="text-sm text-gray-500">{item.variant.name}: {item.variant.value}</p>}
                  <p className="text-brand-600 font-semibold mt-1">{formatPrice(item.price)}</p>
                  <div className="flex items-center gap-3 mt-2">
                    <button onClick={() => updateQty(item.id, item.quantity - 1)} className="w-8 h-8 border rounded">−</button>
                    <span>{item.quantity}</span>
                    <button onClick={() => updateQty(item.id, item.quantity + 1)} className="w-8 h-8 border rounded">+</button>
                    <button onClick={() => remove(item.id)} className="text-red-500 text-sm ml-auto">Remove</button>
                  </div>
                </div>
              </div>
            ))}
          </div>
          <div className="card p-6 h-fit">
            <h2 className="font-bold text-lg mb-4">Order Summary</h2>
            <div className="space-y-2 text-sm mb-4">
              <div className="flex justify-between"><span>Subtotal</span><span>{formatPrice(cart.subtotal)}</span></div>
              <div className="flex justify-between"><span>Tax</span><span>{formatPrice(cart.tax)}</span></div>
              <div className="flex justify-between"><span>Shipping</span><span>{formatPrice(cart.shipping)}</span></div>
              {cart.discount > 0 && <div className="flex justify-between text-green-600"><span>Discount</span><span>-{formatPrice(cart.discount)}</span></div>}
            </div>
            <div className="flex gap-2 mb-4">
              <input className="input text-sm flex-1" placeholder="Coupon code" value={coupon} onChange={(e) => setCoupon(e.target.value)} />
              <button onClick={applyCoupon} className="btn-outline text-sm py-2">Apply</button>
            </div>
            <div className="flex justify-between font-bold text-lg border-t pt-4 mb-4">
              <span>Total</span><span className="text-brand-600">{formatPrice(cart.total)}</span>
            </div>
            <Link to="/checkout" className="btn-primary w-full text-center block">Proceed to Checkout</Link>
          </div>
        </div>
      )}
    </div>
  );
}
