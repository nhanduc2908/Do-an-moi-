import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../widgets/dashboard/statistic_card.dart';

class AIDashboardScreen extends ConsumerStatefulWidget {
  const AIDashboardScreen({super.key});

  @override
  ConsumerState<AIDashboardScreen> createState() => _AIDashboardScreenState();
}

class _AIDashboardScreenState extends ConsumerState<AIDashboardScreen> {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('AI Engine Dashboard'),
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16),
        child: Column(
          children: [
            GridView.count(
              shrinkWrap: true,
              physics: const NeverScrollableScrollPhysics(),
              crossAxisCount: 2,
              mainAxisSpacing: 16,
              crossAxisSpacing: 16,
              childAspectRatio: 1.5,
              children: [
                StatisticCard(
                  title: 'Threats Detected',
                  value: '1,234',
                  icon: Icons.warning,
                  color: Colors.red,
                ),
                StatisticCard(
                  title: 'Anomalies',
                  value: '456',
                  icon: Icons.analytics,
                  color: Colors.orange,
                ),
                StatisticCard(
                  title: 'AI Confidence',
                  value: '94%',
                  icon: Icons.psychology,
                  color: Colors.blue,
                ),
                StatisticCard(
                  title: 'Predictions',
                  value: '89',
                  icon: Icons.trending_up,
                  color: Colors.green,
                ),
              ],
            ),
            const SizedBox(height: 16),
            Card(
              child: Padding(
                padding: const EdgeInsets.all(16),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const Text('Recent AI Detections', style: TextStyle(fontWeight: FontWeight.bold)),
                    const SizedBox(height: 8),
                    _buildDetectionItem('Anomaly detected in network traffic', 'High', '5 min ago'),
                    _buildDetectionItem('Suspicious user behavior', 'Medium', '15 min ago'),
                    _buildDetectionItem('Potential malware signature', 'Critical', '1 hour ago'),
                  ],
                ),
              ),
            ),
            const SizedBox(height: 16),
            Row(
              children: [
                Expanded(
                  child: Card(
                    child: InkWell(
                      onTap: () {},
                      child: const Padding(
                        padding: EdgeInsets.all(16),
                        child: Column(
                          children: [
                            Icon(Icons.chat, size: 32, color: Colors.blue),
                            SizedBox(height: 8),
                            Text('AI Chatbot'),
                          ],
                        ),
                      ),
                    ),
                  ),
                ),
                const SizedBox(width: 12),
                Expanded(
                  child: Card(
                    child: InkWell(
                      onTap: () {},
                      child: const Padding(
                        padding: EdgeInsets.all(16),
                        child: Column(
                          children: [
                            Icon(Icons.trending_up, size: 32, color: Colors.green),
                            SizedBox(height: 8),
                            Text('Predictions'),
                          ],
                        ),
                      ),
                    ),
                  ),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildDetectionItem(String text, String severity, String time) {
    Color color = severity == 'Critical' ? Colors.red : (severity == 'High' ? Colors.orange : Colors.blue);
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 8),
      child: Row(
        children: [
          Container(width: 8, height: 8, decoration: BoxDecoration(shape: BoxShape.circle, color: color)),
          const SizedBox(width: 12),
          Expanded(child: Text(text)),
          Text(time, style: const TextStyle(fontSize: 12, color: Colors.grey)),
        ],
      ),
    );
  }
}