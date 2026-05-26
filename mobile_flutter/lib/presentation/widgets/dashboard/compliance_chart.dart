import 'package:flutter/material.dart';
import 'package:fl_chart/fl_chart.dart';

class ComplianceChart extends StatelessWidget {
  final Map<String, double> data;

  const ComplianceChart({super.key, required this.data});

  @override
  Widget build(BuildContext context) {
    final entries = data.entries.toList();
    return SizedBox(
      height: 200,
      child: BarChart(
        BarChartData(
          alignment: BarChartAlignment.spaceAround,
          maxY: 100,
          titlesData: FlTitlesData(
            leftTitles: AxisTitles(
              sideTitles: SideTitles(showTitles: true, reservedSize: 40),
            ),
            bottomTitles: AxisTitles(
              sideTitles: SideTitles(
                showTitles: true,
                getTitlesWidget: (value, meta) {
                  if (value.toInt() >= 0 && value.toInt() < entries.length) {
                    return Text(entries[value.toInt()].key, style: const TextStyle(fontSize: 10));
                  }
                  return const Text('');
                },
              ),
            ),
          ),
          barGroups: entries.asMap().entries.map((entry) {
            return BarChartGroupData(
              x: entry.key,
              barRods: [
                BarChartRodData(
                  toY: entry.value,
                  color: Colors.blue,
                  width: 30,
                ),
              ],
            );
          }).toList(),
        ),
      ),
    );
  }
}