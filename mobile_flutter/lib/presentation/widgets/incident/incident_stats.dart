// Đường dẫn: mobile_flutter/lib/presentation/widgets/incident/incident_stats.dart

import 'package:flutter/material.dart';

class IncidentStats extends StatelessWidget {
  final int total;
  final int open;
  final int investigating;
  final int resolved;

  const IncidentStats({
    super.key,
    required this.total,
    required this.open,
    required this.investigating,
    required this.resolved,
  });

  @override
  Widget build(BuildContext context) {
    return Row(
      children: [
        _buildStatCard('Total', total, Colors.blue),
        _buildStatCard('Open', open, Colors.red),
        _buildStatCard('Investigating', investigating, Colors.orange),
        _buildStatCard('Resolved', resolved, Colors.green),
      ],
    );
  }

  Widget _buildStatCard(String title, int value, Color color) {
    return Expanded(
      child: Card(
        margin: const EdgeInsets.symmetric(horizontal: 4),
        child: Padding(
          padding: const EdgeInsets.all(12),
          child: Column(
            children: [
              Text(
                value.toString(),
                style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold, color: color),
              ),
              const SizedBox(height: 4),
              Text(title, style: const TextStyle(fontSize: 12, color: Colors.grey)),
            ],
          ),
        ),
      ),
    );
  }
}