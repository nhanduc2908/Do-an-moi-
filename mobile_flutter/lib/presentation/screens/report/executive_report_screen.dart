import 'package:flutter/material.dart';
import 'package:fl_chart/fl_chart.dart';

class ExecutiveReportScreen extends StatelessWidget {
  const ExecutiveReportScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Executive Report'),
      ),
      body: ListView(
        padding: const EdgeInsets.all(16),
        children: [
          Card(
            child: Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                children: [
                  const Text('Security Score Trend', style: TextStyle(fontWeight: FontWeight.bold)),
                  const SizedBox(height: 16),
                  SizedBox(
                    height: 200,
                    child: LineChart(
                      LineChartData(
                        gridData: FlGridData(show: false),
                        titlesData: FlTitlesData(
                          leftTitles: AxisTitles(sideTitles: SideTitles(showTitles: true)),
                          bottomTitles: AxisTitles(
                            sideTitles: SideTitles(
                              showTitles: true,
                              getTitlesWidget: (value, meta) {
                                const labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
                                return Text(labels[value.toInt()]);
                              },
                            ),
                          ),
                        ),
                        lineBarsData: [
                          LineChartBarData(
                            spots: const [
                              FlSpot(0, 65), FlSpot(1, 70), FlSpot(2, 68),
                              FlSpot(3, 72), FlSpot(4, 75), FlSpot(5, 78),
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
          const SizedBox(height: 16),
          Card(
            child: Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                children: [
                  const Text('Key Metrics', style: TextStyle(fontWeight: Weight.bold)),
                  const SizedBox(height: 16),
                  _buildMetricRow('Total Assessments', '45', '↑ 12%'),
                  _buildMetricRow('Open Incidents', '12', '↓ 3'),
                  _buildMetricRow('Vulnerabilities', '127', '↑ 5'),
                  _buildMetricRow('Compliance Rate', '85%', '↑ 4%'),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildMetricRow(String label, String value, String trend) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 8),
      child: Row(
        children: [
          Expanded(child: Text(label)),
          Text(value, style: const TextStyle(fontWeight: FontWeight.bold)),
          const SizedBox(width: 16),
          Text(
            trend,
            style: TextStyle(
              color: trend.contains('↑') ? Colors.green : Colors.red,
              fontWeight: FontWeight.bold,
            ),
          ),
        ],
      ),
    );
  }
}