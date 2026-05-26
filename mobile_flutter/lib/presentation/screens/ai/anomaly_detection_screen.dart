import 'package:flutter/material.dart';
import 'package:fl_chart/fl_chart.dart';

class AnomalyDetectionScreen extends StatefulWidget {
  const AnomalyDetectionScreen({super.key});

  @override
  State<AnomalyDetectionScreen> createState() => _AnomalyDetectionScreenState();
}

class _AnomalyDetectionScreenState extends State<AnomalyDetectionScreen> {
  final List<Map<String, dynamic>> _anomalies = [
    {'metric': 'Network Traffic', 'value': '245 MB/s', 'baseline': '120 MB/s', 'severity': 'High', 'time': '10:30'},
    {'metric': 'Login Attempts', 'value': '156', 'baseline': '45', 'severity': 'Critical', 'time': '09:45'},
    {'metric': 'CPU Usage', 'value': '95%', 'baseline': '45%', 'severity': 'High', 'time': '08:20'},
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Anomaly Detection'),
      ),
      body: ListView(
        children: [
          Card(
            margin: const EdgeInsets.all(16),
            child: Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                children: [
                  const Text('Network Traffic Anomaly', style: TextStyle(fontWeight: FontWeight.bold)),
                  const SizedBox(height: 16),
                  SizedBox(
                    height: 200,
                    child: LineChart(
                      LineChartData(
                        gridData: FlGridData(show: true),
                        titlesData: FlTitlesData(
                          leftTitles: AxisTitles(sideTitles: SideTitles(showTitles: true)),
                          bottomTitles: AxisTitles(
                            sideTitles: SideTitles(
                              showTitles: true,
                              getTitlesWidget: (value, meta) {
                                const labels = ['00', '04', '08', '12', '16', '20'];
                                return Text(labels[value.toInt()]);
                              },
                            ),
                          ),
                        ),
                        lineBarsData: [
                          LineChartBarData(
                            spots: const [
                              FlSpot(0, 45), FlSpot(1, 52), FlSpot(2, 48),
                              FlSpot(3, 220), FlSpot(4, 55), FlSpot(5, 50),
                            ],
                            isCurved: true,
                            color: Colors.blue,
                            barWidth: 3,
                            belowBarData: BarAreaData(show: true, color: Colors.blue.withOpacity(0.1)),
                          ),
                        ],
                      ),
                    ),
                  ),
                ],
              ),
            ),
          ),
          const Padding(
            padding: EdgeInsets.all(16),
            child: Text('Recent Anomalies', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
          ),
          ..._anomalies.map((anomaly) => Card(
            margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 4),
            child: ListTile(
              leading: CircleAvatar(
                backgroundColor: anomaly['severity'] == 'Critical' ? Colors.red : Colors.orange,
                child: const Icon(Icons.warning, color: Colors.white),
              ),
              title: Text(anomaly['metric'], style: const TextStyle(fontWeight: FontWeight.bold)),
              subtitle: Text('Value: ${anomaly['value']} • Baseline: ${anomaly['baseline']}'),
              trailing: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Text(anomaly['time']),
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
                    decoration: BoxDecoration(
                      color: anomaly['severity'] == 'Critical' ? Colors.red.shade100 : Colors.orange.shade100,
                      borderRadius: BorderRadius.circular(12),
                    ),
                    child: Text(anomaly['severity'], style: const TextStyle(fontSize: 10)),
                  ),
                ],
              ),
            ),
          )),
        ],
      ),
    );
  }
}