import 'package:flutter/material.dart';
import 'package:fl_chart/fl_chart.dart';

class SiemDashboardScreen extends StatefulWidget {
  const SiemDashboardScreen({super.key});

  @override
  State<SiemDashboardScreen> createState() => _SiemDashboardScreenState();
}

class _SiemDashboardScreenState extends State<SiemDashboardScreen> {
  int _selectedTimeRange = 0; // 0: 24h, 1: 7d, 2: 30d
  
  final List<Map<String, dynamic>> _recentEvents = [
    {'time': '10:30', 'event': 'Failed Login', 'source': '192.168.1.100', 'severity': 'Medium'},
    {'time': '10:15', 'event': 'Malware Detected', 'source': 'Endpoint-05', 'severity': 'Critical'},
    {'time': '09:45', 'event': 'Suspicious Connection', 'source': '10.0.0.25', 'severity': 'High'},
    {'time': '09:20', 'event': 'Policy Violation', 'source': 'User: jdoe', 'severity': 'Low'},
    {'time': '08:55', 'event': 'Firewall Block', 'source': '203.0.113.45', 'severity': 'Medium'},
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('SIEM Dashboard'),
        actions: [
          IconButton(
            icon: const Icon(Icons.filter_list),
            onPressed: () {},
          ),
          IconButton(
            icon: const Icon(Icons.download),
            onPressed: () {},
          ),
        ],
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
                  const Text('Event Timeline', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                  const SizedBox(height: 16),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceEvenly,
                    children: [
                      _buildTimeRangeButton('24h', 0),
                      _buildTimeRangeButton('7d', 1),
                      _buildTimeRangeButton('30d', 2),
                    ],
                  ),
                  const SizedBox(height: 16),
                  SizedBox(
                    height: 200,
                    child: LineChart(
                      LineChartData(
                        gridData: FlGridData(show: false),
                        titlesData: FlTitlesData(
                          leftTitles: AxisTitles(
                            sideTitles: SideTitles(showTitles: true, reservedSize: 40),
                          ),
                          bottomTitles: AxisTitles(
                            sideTitles: SideTitles(
                              showTitles: true,
                              getTitlesWidget: (value, meta) {
                                const labels = ['00', '04', '08', '12', '16', '20'];
                                if (value.toInt() >= 0 && value.toInt() < labels.length) {
                                  return Text(labels[value.toInt()]);
                                }
                                return const Text('');
                              },
                            ),
                          ),
                        ),
                        borderData: FlBorderData(show: false),
                        lineBarsData: [
                          LineChartBarData(
                            spots: const [
                              FlSpot(0, 12), FlSpot(1, 18), FlSpot(2, 15), FlSpot(3, 25),
                              FlSpot(4, 22), FlSpot(5, 30),
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
                  const Text('Alert Distribution', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                  const SizedBox(height: 16),
                  SizedBox(
                    height: 200,
                    child: PieChart(
                      PieChartData(
                        sections: [
                          PieChartSectionData(value: 45, title: 'Critical', color: Colors.red),
                          PieChartSectionData(value: 30, title: 'High', color: Colors.orange),
                          PieChartSectionData(value: 15, title: 'Medium', color: Colors.yellow.shade700),
                          PieChartSectionData(value: 10, title: 'Low', color: Colors.green),
                        ],
                        centerSpaceRadius: 40,
                      ),
                    ),
                  ),
                ],
              ),
            ),
          ),
          const SizedBox(height: 16),
          const Text('Recent Events', style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
          const SizedBox(height: 8),
          ..._recentEvents.map((event) => Card(
            margin: const EdgeInsets.only(bottom: 8),
            child: ListTile(
              leading: CircleAvatar(
                backgroundColor: _getSeverityColor(event['severity']),
                child: Icon(_getSeverityIcon(event['severity']), color: Colors.white, size: 20),
              ),
              title: Text(event['event'], style: const TextStyle(fontWeight: FontWeight.bold)),
              subtitle: Text('${event['source']} • ${event['time']}'),
              trailing: Chip(
                label: Text(event['severity']),
                backgroundColor: _getSeverityColor(event['severity']).withOpacity(0.2),
              ),
              onTap: () {},
            ),
          )),
        ],
      ),
    );
  }

  Widget _buildTimeRangeButton(String label, int index) {
    return FilterChip(
      label: Text(label),
      selected: _selectedTimeRange == index,
      onSelected: (selected) => setState(() => _selectedTimeRange = index),
    );
  }

  Color _getSeverityColor(String severity) {
    switch (severity) {
      case 'Critical': return Colors.red;
      case 'High': return Colors.orange;
      case 'Medium': return Colors.yellow.shade700;
      default: return Colors.green;
    }
  }

  IconData _getSeverityIcon(String severity) {
    switch (severity) {
      case 'Critical': return Icons.error;
      case 'High': return Icons.warning;
      default: return Icons.info;
    }
  }
}