// Đường dẫn: mobile_flutter/lib/presentation/widgets/dashboard/trend_chart.dart

import 'package:flutter/material.dart';
import 'package:fl_chart/fl_chart.dart';

class TrendChart extends StatelessWidget {
  final List<double> data;
  final List<String> labels;
  final String title;
  final Color color;

  const TrendChart({
    super.key,
    required this.data,
    required this.labels,
    required this.title,
    this.color = Colors.blue,
  });

  @override
  Widget build(BuildContext context) {
    return Card(
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(title, style: const TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
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
                      spots: data.asMap().entries.map((e) => FlSpot(e.key.toDouble(), e.value)).toList(),
                      isCurved: true,
                      color: color,
                      barWidth: 3,
                      belowBarData: BarAreaData(show: true, color: color.withOpacity(0.1)),
                    ),
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}