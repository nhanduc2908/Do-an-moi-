import 'package:flutter/material.dart';
import '../../../../widgets/common/custom_chart.dart';

class SystemMonitorScreen extends StatefulWidget {
  const SystemMonitorScreen({super.key});

  @override
  State<SystemMonitorScreen> createState() => _SystemMonitorScreenState();
}

class _SystemMonitorScreenState extends State<SystemMonitorScreen> {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('System Monitor')),
      body: RefreshIndicator(
        onRefresh: () async {},
        child: ListView(
          padding: const EdgeInsets.all(16),
          children: [
            Card(
              child: Padding(
                padding: const EdgeInsets.all(16),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const Text('CPU Usage', style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
                    const SizedBox(height: 8),
                    LinearProgressIndicator(value: 0.45, backgroundColor: Colors.grey[300]),
                    const SizedBox(height: 4),
                    const Text('45%', style: TextStyle(color: Colors.grey)),
                  ],
                ),
              ),
            ),
            const SizedBox(height: 12),
            Card(
              child: Padding(
                padding: const EdgeInsets.all(16),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const Text('Memory Usage', style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
                    const SizedBox(height: 8),
                    LinearProgressIndicator(value: 0.62, backgroundColor: Colors.grey[300]),
                    const SizedBox(height: 4),
                    const Text('62%', style: TextStyle(color: Colors.grey)),
                  ],
                ),
              ),
            ),
            const SizedBox(height: 12),
            Card(
              child: Padding(
                padding: const EdgeInsets.all(16),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const Text('Disk Usage', style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
                    const SizedBox(height: 8),
                    LinearProgressIndicator(value: 0.58, backgroundColor: Colors.grey[300]),
                    const SizedBox(height: 4),
                    const Text('58%', style: TextStyle(color: Colors.grey)),
                  ],
                ),
              ),
            ),
            const SizedBox(height: 12),
            Card(
              child: Padding(
                padding: const EdgeInsets.all(16),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const Text('System Load - Last 7 Days', style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold)),
                    const SizedBox(height: 16),
                    SizedBox(
                      height: 200,
                      child: CustomChart(
                        data: [45, 52, 48, 55, 50, 42, 38],
                        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                      ),
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