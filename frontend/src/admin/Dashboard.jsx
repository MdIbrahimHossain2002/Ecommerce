import { useEffect, useState } from 'react';
import api from '../api/axios';
import { formatPrice } from '../utils/format';

export default function Dashboard() {
  const [data, setData] = useState(null);

  useEffect(() => {
    api.get('/admin/dashboard').then((r) => setData(r.data)).catch(console.error);
  }, []);

  if (!data) return <div>Loading dashboard...</div>;

  const stats = [
    { label: 'Total Revenue', value: formatPrice(data.stats.totalRevenue) },
    { label: 'Total Orders', value: data.stats.totalOrders },
    { label: 'Customers', value: data.stats.totalCustomers },
    { label: 'Products', value: data.stats.totalProducts },
    { label: 'Low Stock', value: data.stats.lowStock, alert: true },
  ];

  return (
    <div>
      <h1 className="text-2xl font-bold mb-6">Dashboard</h1>
      <div className="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
        {stats.map((s) => (
          <div key={s.label} className={`card p-4 ${s.alert && s.value > 0 ? 'border-red-300' : ''}`}>
            <p className="text-sm text-gray-500">{s.label}</p>
            <p className="text-2xl font-bold mt-1">{s.value}</p>
          </div>
        ))}
      </div>
      <h2 className="font-semibold mb-4">Recent Orders</h2>
      <div className="card overflow-hidden">
        <table className="w-full text-sm">
          <thead className="bg-gray-50"><tr><th className="p-3 text-left">Order</th><th className="p-3 text-left">Customer</th><th className="p-3 text-left">Total</th><th className="p-3 text-left">Status</th></tr></thead>
          <tbody>
            {data.recent_orders?.map((o) => (
              <tr key={o.id} className="border-t">
                <td className="p-3">{o.order_number}</td>
                <td className="p-3">{o.user?.name}</td>
                <td className="p-3">{formatPrice(o.total)}</td>
                <td className="p-3 capitalize">{o.status}</td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
}
