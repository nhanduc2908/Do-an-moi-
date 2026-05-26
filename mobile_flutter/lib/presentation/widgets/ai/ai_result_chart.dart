// Đường dẫn: mobile_flutter/lib/presentation/widgets/ai/ai_result_chart.dart

import 'package:flutter/material.dart';
import 'package:fl_chart/fl_chart.dart';

class AIResultChart extends StatelessWidget {
  final List<double> actual;
  final List<double> predicted;
  final List<String> labels;

  const AIResultChart({
    super.key,
    required this.actual,
    required this.predicted,
    required this.labels,
  });

  @override
  Widget build(BuildContext context) {
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Text('AI Prediction vs Actual', style: TextStyle(fontWeight: FontWeight.bold)),
            const SizedBox(height: 16),
            SizedBox(
              height: 200,
              child: LineChart(
                LineChartData(
                  gridData: FlGridData(show: true),
                  titlesData: FlTitlesData(
                    leftTitles: AxisTitles(
                      sideTitles: SideTitles(showTitles: true, reservedSize: 40),
                    ),
                    bottomTitles: AxisTitles(
                      sideTitles: SideTitles(
                        showTitles: true,
                        getTitlesWidget: (value, meta) {
                          if (value.toInt() >= 0 && value.toInt() < labels.length) {
                            return Text(labels[value.toInt()], style: const TextStyle(fontSize: 10));
                          }
                          return const Text('');
                        },
                      ),
                    ),
                  ),
                  lineBarsData: [
                    LineChartBarData(
                      spots: actual.asMap().entries.map((e) => FlSpot(e.key.toDouble(), e.value)).toList(),
                      isCurved: true,
                      color: Colors.blue,
                      barWidth: 3,
                      belowBarData: BarAreaData(show: false),
                    ),
                    LineChartBarData(
                      spots: predicted.asMap().entries.map((e) => FlSpot(e.key.toDouble(), e.value)).toList(),
                      isCurved: true,
                      color: Colors.red,
                      barWidth: 3,
                      belowBarData: BarAreaData(show: false),
                      dashArray: [5, 5],
                    ),
                  ],
                ),
              ),
            ),
            const SizedBox(height: 8),
            Row(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                _buildLegend('Actual', Colors.blue),
                const SizedBox(width: 16),
                _buildLegend('Predicted', Colors.red),
              ],
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildLegend(String text, Color color) {
    return Row(
      children: [
        Container(width: 16, height: 2, color: color),
        const SizedBox(width: 4),
        Text(text, style: const TextStyle(fontSize: 10)),
      ],
    );
  }
}