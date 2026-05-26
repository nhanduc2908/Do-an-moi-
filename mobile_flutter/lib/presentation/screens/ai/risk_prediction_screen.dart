import 'package:flutter/material.dart';
import 'package:fl_chart/fl_chart.dart';

class RiskPredictionScreen extends StatelessWidget {
  const RiskPredictionScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Risk Prediction'),
      ),
      body: ListView(
        padding: const EdgeInsets.all(16),
        children: [
          Card(
            child: Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                children: [
                  const Text('Predicted Risk Score', style: TextStyle(fontWeight: FontWeight.bold)),
                  const SizedBox(height: 16),
                  const Text('68', style: TextStyle(fontSize: 48, fontWeight: FontWeight.bold, color: Colors.orange)),
                  const SizedBox(height: 8),
                  const Text('Medium Risk Level'),
                  const SizedBox(height: 16),
                  LinearProgressIndicator(value: 0.68, backgroundColor: Colors.grey.shade200),
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
                  const Text('Risk Trend (Next 30 Days)', style: TextStyle(fontWeight: FontWeight.bold)),
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
                                const labels = ['Week 1', 'Week 2', 'Week 3', 'Week 4'];
                                return Text(labels[value.toInt()]);
                              },
                            ),
                          ),
                        ),
                        lineBarsData: [
                          LineChartBarData(
                            spots: const [
                              FlSpot(0, 45), FlSpot(1, 52), FlSpot(2, 58),
                              FlSpot(3, 65), FlSpot(4, 68),
                            ],
                            isCurved: true,
                            color: Colors.orange,
                            barWidth: 3,
                            belowBarData: BarAreaData(show: true, color: Colors.orange.withOpacity(0.1)),
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
                crossAxisAlignment: CrossAxisAlignment.start,
                children: const [
                  Text('Recommendations', style: TextStyle(fontWeight: FontWeight.bold)),
                  SizedBox(height: 8),
                  Text('• Implement additional access controls'),
                  Text('• Enhance monitoring capabilities'),
                  Text('• Schedule security training'),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }
}