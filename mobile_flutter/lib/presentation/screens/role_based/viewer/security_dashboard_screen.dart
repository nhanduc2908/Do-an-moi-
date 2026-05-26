import 'package:flutter/material.dart';
import 'package:fl_chart/fl_chart.dart';

class SecurityDashboardScreen extends StatefulWidget {
  const SecurityDashboardScreen({super.key});

  @override
  State<SecurityDashboardScreen> createState() => _SecurityDashboardScreenState();
}

class _SecurityDashboardScreenState extends State<SecurityDashboardScreen> {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Security Dashboard'),
      ),
      body: ListView(
        padding: const EdgeInsets.all(16),
        children: [
          Card(
            child: Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
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
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text('Risk Distribution', style: TextStyle(fontWeight: FontWeight.bold)),
                  const SizedBox(height: 16),
                  SizedBox(
                    height: 200,
                    child: PieChart(
                      PieChartData(
                        sections: [
                          PieChartSectionData(value: 12, title: 'Critical', color: Colors.red),
                          PieChartSectionData(value: 25, title: 'High', color: Colors.orange),
                          PieChartSectionData(value: 35, title: 'Medium', color: Colors.yellow.shade700),
                          PieChartSectionData(value: 28, title: 'Low', color: Colors.green),
                        ],
                        centerSpaceRadius: 40,
                      ),
                    ),
                  ),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }
}