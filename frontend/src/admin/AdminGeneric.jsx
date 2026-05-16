import { useEffect, useState } from 'react';
import api from '../api/axios';

export default function AdminGeneric({ title, endpoint, columns }) {
  const [items, setItems] = useState([]);

  useEffect(() => {
    api.get(endpoint).then((r) => setItems(r.data.data || r.data || []));
  }, [endpoint]);

  return (
    <div>
      <h1 className="text-2xl font-bold mb-6">{title}</h1>
      <div className="card overflow-hidden">
        <table className="w-full text-sm">
          <thead className="bg-gray-50">
            <tr>{columns.map((c) => <th key={c.key} className="p-3 text-left">{c.label}</th>)}</tr>
          </thead>
          <tbody>
            {items.map((item) => (
              <tr key={item.id} className="border-t">
                {columns.map((c) => (
                  <td key={c.key} className="p-3">{c.render ? c.render(item) : item[c.key]}</td>
                ))}
              </tr>
            ))}
          </tbody>
        </table>
        {items.length === 0 && <p className="p-6 text-center text-gray-500">No items found.</p>}
      </div>
    </div>
  );
}
