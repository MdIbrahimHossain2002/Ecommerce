import { useEffect, useState } from 'react';
import api from '../api/axios';
import { formatPrice } from '../utils/format';

const STATUSES = ['pending', 'processing', 'shipped', 'delivered', 'cancelled', 'returned', 'refunded'];

export default function AdminOrders() {
  const [orders, setOrders] = useState([]);
  const [filter, setFilter] = useState('');

  const load = () => api.get('/admin/orders', { params: filter ? { status: filter } : {} }).then((r) => setOrders(r.data.data || []));

  useEffect(() => { load(); }, [filter]);

  const updateStatus = async (id, status) => {
    await api.patch(`/admin/orders/${id}/status`, { status });
    load();
  };

  return (
    <div>
      <h1 className="text-2xl font-bold mb-6">Orders</h1>
      <select className="input w-48 mb-4" value={filter} onChange={(e) => setFilter(e.target.value)}>
        <option value="">All Status</option>
        {STATUSES.map((s) => <option key={s} value={s}>{s}</option>)}
      </select>
      <div className="card overflow-hidden">
        <table className="w-full text-sm">
          <thead className="bg-gray-50"><tr><th className="p-3 text-left">Order</th><th className="p-3 text-left">Customer</th><th className="p-3 text-left">Total</th><th className="p-3 text-left">Status</th><th className="p-3 text-left">Action</th></tr></thead>
          <tbody>
            {orders.map((o) => (
              <tr key={o.id} className="border-t">
                <td className="p-3">{o.order_number}</td>
                <td className="p-3">{o.user?.name}</td>
                <td className="p-3">{formatPrice(o.total)}</td>
                <td className="p-3 capitalize">{o.status}</td>
                <td className="p-3">
                  <select className="text-sm border rounded px-2 py-1" value={o.status} onChange={(e) => updateStatus(o.id, e.target.value)}>
                    {STATUSES.map((s) => <option key={s} value={s}>{s}</option>)}
                  </select>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
}
