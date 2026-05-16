import { useEffect, useState } from 'react';
import api from '../api/axios';

export default function AdminCustomers() {
  const [customers, setCustomers] = useState([]);

  useEffect(() => {
    api.get('/admin/customers').then((r) => setCustomers(r.data.data || []));
  }, []);

  const toggleStatus = async (id, status) => {
    await api.patch(`/admin/customers/${id}/status`, { status: status === 'active' ? 'banned' : 'active' });
    const { data } = await api.get('/admin/customers');
    setCustomers(data.data || []);
  };

  return (
    <div>
      <h1 className="text-2xl font-bold mb-6">Customers</h1>
      <div className="card overflow-hidden">
        <table className="w-full text-sm">
          <thead className="bg-gray-50"><tr><th className="p-3 text-left">Name</th><th className="p-3 text-left">Email</th><th className="p-3 text-left">Orders</th><th className="p-3 text-left">Status</th><th className="p-3"></th></tr></thead>
          <tbody>
            {customers.map((c) => (
              <tr key={c.id} className="border-t">
                <td className="p-3">{c.name}</td>
                <td className="p-3">{c.email}</td>
                <td className="p-3">{c.orders_count}</td>
                <td className="p-3 capitalize">{c.status}</td>
                <td className="p-3">
                  <button onClick={() => toggleStatus(c.id, c.status)} className="text-sm text-brand-600">
                    {c.status === 'active' ? 'Block' : 'Unblock'}
                  </button>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </div>
  );
}
