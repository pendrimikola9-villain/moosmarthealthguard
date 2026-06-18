import { useEffect, useRef } from "react";

function Grafik({ data, selectedSapi, setSelectedSapi }) {
  const suhuRef = useRef(null);
  const jantungRef = useRef(null);
  const suhuChart = useRef(null);
  const jantungChart = useRef(null);

  // SINKRONISASI LABEL: Memetakan teks database (rendah, sedang, tinggi) agar rapi di layar
  const stressLabel = { rendah: "Rendah", sedang: "Sedang", tinggi: "Tinggi" };

  const sapiAktif = selectedSapi
    ? data.find(d => d.cow_id === selectedSapi.cow_id) || data[0]
    : data[0];

  useEffect(() => {
    if (!data || data.length === 0) return;

    const warna = { normal: "#4a7c2f", warning: "#d97706", danger: "#dc2626" };

    const buatGrafik = () => {
      if (suhuRef.current) {
        if (suhuChart.current) suhuChart.current.destroy();
        suhuChart.current = new window.Chart(suhuRef.current, {
          type: "bar",
          data: {
            labels: data.map(d => d.nama),
            datasets: [{
              label: "Suhu (°C)",
              data: data.map(d => parseFloat(d.suhu_celsius)),
              backgroundColor: data.map(d => warna[d.status_suhu] + "99"),
              borderColor: data.map(d => warna[d.status_suhu]),
              borderWidth: 2,
              borderRadius: 6,
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
              y: {
                min: 36, max: 43,
                ticks: { callback: v => v + "°C" },
                grid: { color: "#f1f5f9" }
              },
              x: { grid: { display: false } }
            }
          }
        });
      }

      if (jantungRef.current) {
        if (jantungChart.current) jantungChart.current.destroy();
        jantungChart.current = new window.Chart(jantungRef.current, {
          type: "bar",
          data: {
            labels: data.map(d => d.nama),
            datasets: [{
              label: "Detak Jantung (bpm)",
              data: data.map(d => parseInt(d.detak_jantung_bpm)),
              backgroundColor: data.map(d => warna[d.status_jantung] + "99"),
              borderColor: data.map(d => warna[d.status_jantung]),
              borderWidth: 2,
              borderRadius: 6,
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
              y: {
                min: 50, max: 135,
                ticks: { callback: v => v + " bpm" },
                grid: { color: "#f1f5f9" }
              },
              x: { grid: { display: false } }
            }
          }
        });
      }
    };

    if (window.Chart) {
      buatGrafik();
    } else {
      const interval = setInterval(() => {
        if (window.Chart) { buatGrafik(); clearInterval(interval); }
      }, 200);
    }

    return () => {
      if (suhuChart.current) suhuChart.current.destroy();
      if (jantungChart.current) jantungChart.current.destroy();
    };
  }, [data]);

  return (
    <div>
      <div className="grafik-select-wrap">
        <label className="grafik-label">Pilih Sapi:</label>
        <select
          className="grafik-select"
          value={sapiAktif?.cow_id || ""}
          onChange={e => setSelectedSapi(data.find(d => d.cow_id === e.target.value))}
        >
          {data.map(d => (
            <option key={d.cow_id} value={d.cow_id}>{d.cow_id} - {d.nama}</option>
          ))}
        </select>
      </div>

      {sapiAktif && (
        <div className="grafik-detail">
          <div className="detail-card">
            <div>
              <div className="detail-val">{sapiAktif.suhu_celsius}°C</div>
              <div className="detail-label">Suhu Tubuh</div>
            </div>
          </div>
          <div className="detail-card">
            <div>
              <div className="detail-val">{sapiAktif.detak_jantung_bpm} bpm</div>
              <div className="detail-label">Detak Jantung</div>
            </div>
          </div>
          <div className="detail-card">
            <div>
              {/* PERUBAHAN: Memakai objek mapping stressLabel agar teks tampil rapi dengan huruf kapital */}
              <div className="detail-val">
                {stressLabel[sapiAktif.level_stress] || "Rendah"}
              </div>
              <div className="detail-label">Tingkat Kestressan</div>
            </div>
          </div>
        </div>
      )}

      <div className="grafik-grid">
        <div className="grafik-card">
          <h3 className="grafik-title">Suhu Tubuh Semua Sapi</h3>
          <div style={{ position: "relative", height: "260px" }}>
            <canvas ref={suhuRef} role="img" aria-label="Grafik suhu tubuh sapi"></canvas>
          </div>
        </div>
        <div className="grafik-card">
          <h3 className="grafik-title">Detak Jantung Semua Sapi</h3>
          <div style={{ position: "relative", height: "260px" }}>
            <canvas ref={jantungRef} role="img" aria-label="Grafik detak jantung sapi"></canvas>
          </div>
        </div>
      </div>
    </div>
  );
}

export default Grafik;