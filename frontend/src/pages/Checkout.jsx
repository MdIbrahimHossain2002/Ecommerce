import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import api from '../api/axios';

const PAYMENT_METHODS = [
  { id: 'cod', label: 'Cash on Delivery' },
  { id: 'bkash', label: 'bKash' },
  { id: 'nagad', label: 'Nagad' },
  { id: 'rocket', label: 'Rocket' },
  { id: 'sslcommerz', label: 'SSLCommerz' },
  { id: 'stripe', label: 'Stripe' },
  { id: 'paypal', label: 'PayPal' },
  { id: 'bank_transfer', label: 'Bank Transfer' },
];

export default function Checkout() {
  const navigate = useNavigate();
  const [form, setForm] = useState({
    name: '', phone: '', address_line: '', city: '', postal_code: '', country: 'Bangladesh',
    payment_method: 'cod', notes: '',
  });
  const [loading, setLoading] = useState(false);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    const address = { name: form.name, phone: form.phone, address_line: form.address_line, city: form.city, postal_code: form.postal_code, country: form.country };
    try {
      const { data } = await api.post('/orders', {
        payment_method: form.payment_method,
        billing_address: address,
        shipping_address: address,
        notes: form.notes,
      });
      navigate(`/orders/${data.order_number}`);
    } catch (err) {
      alert(err.response?.data?.message || 'Checkout failed');
    } finally {
      setLoading(false);
    }
  };

  const set = (k) => (e) => setForm({ ...form, [k]: e.target.value });

  return (
    <div className="max-w-2xl mx-auto px-4 py-8">
      <h1 className="text-3xl font-bold mb-6">Checkout</h1>
      <form onSubmit={handleSubmit} className="card p-6 space-y-4">
        <h2 className="font-semibold text-lg">Shipping Address</h2>
        <input className="input" placeholder="Full Name" value={form.name} onChange={set('name')} required />
        <input className="input" placeholder="Phone" value={form.phone} onChange={set('phone')} required />
        <input className="input" placeholder="Address" value={form.address_line} onChange={set('address_line')} required />
        <div className="grid grid-cols-2 gap-4">
          <input className="input" placeholder="City" value={form.city} onChange={set('city')} required />
          <input className="input" placeholder="Postal Code" value={form.postal_code} onChange={set('postal_code')} required />
        </div>
        <h2 className="font-semibold text-lg pt-2">Payment Method</h2>
        <div className="grid grid-cols-2 gap-2">
          {PAYMENT_METHODS.map((m) => (
            <label key={m.id} className={`flex items-center gap-2 p-3 border rounded-lg cursor-pointer ${form.payment_method === m.id ? 'border-brand-600 bg-brand-50' : ''}`}>
              <input type="radio" name="payment" value={m.id} checked={form.payment_method === m.id} onChange={set('payment_method')} />
              {m.label}
            </label>
          ))}
        </div>
        <textarea className="input" placeholder="Order notes (optional)" rows={3} value={form.notes} onChange={set('notes')} />
        <button type="submit" className="btn-primary w-full" disabled={loading}>
          {loading ? 'Placing Order...' : 'Place Order'}
        </button>
      </form>
    </div>
  );
}
