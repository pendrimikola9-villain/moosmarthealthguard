function Riwayat({ data }) {
  const warna = { normal: "#4a7c2f", warning: "#d97706", danger: "#dc2626" };
  const label = { normal: "Normal", warning: "Waspada", danger: "Bahaya" };
  
  // SINKRONISASI: Menyesuaikan dengan data aktual database (rendah, sedang, tinggi)
  const stressWarna = { rendah: "#4a7c2f", sedang: "#d97706", tinggi: "#dc2626" };
  const stressLabel = { rendah: "Rendah", sedang: "Sedang", tinggi: "Tinggi" };

  return (
    <div className="riwayat-wrap">
      <table className="riwayat-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Suhu</th>
            <th>Status Suhu</th>
            <th>Detak Jantung</th>
            <th>Status Jantung</th>
            <th>Aktivitas & Kestresan</th>
            <th>Timestamp</th>
          </tr>
        </thead>
        <tbody>
          {data.map(d => (
            <tr key={d.cow_id}>
              <td><span className="cow-id">{d.cow_id}</span></td>
              <td>{d.nama}</td>
              <td><strong>{d.suhu_celsius}°C</strong></td>
              <td><span className="badge-small" style={{ background: warna[d.status_suhu] }}>{label[d.status_suhu]}</span></td>
              <td><strong>{d.detak_jantung_bpm} bpm</strong></td>
              <td><span className="badge-small" style={{ background: warna[d.status_jantung] }}>{label[d.status_jantung]}</span></td>
              <td>
                <span style={{ marginRight: "8px", textTransform: "capitalize" }}>
                  {d.nilai_gerakan || "-"} 
                </span>
                <span className="badge-small" style={{ background: stressWarna[d.level_stress] }}>
                  {stressLabel[d.level_stress] || "Rendah"}
                </span>
              </td>
              <td className="timestamp">{d.timestamp}</td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
}

export default Riwayat;