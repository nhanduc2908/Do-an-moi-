// Đường dẫn: mobile_flutter/lib/presentation/widgets/dashboard/kpi_card.dart

import 'package:flutter/material.dart';

class KpiCard extends StatelessWidget {
  final String title;
  final String value;
  final String? change;
  final IconData icon;
  final Color color;

  const KpiCard({
    super.key,
    required this.title,
    required this.value,
    this.change,
    required this.icon,
    required this.color,
  });

  @override
  Widget build(BuildContext context) {
    return Card(
      elevation: 2,
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
      child: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Text(title, style: const TextStyle(fontSize: 14, color: Colors.grey)),
                Icon(icon, size: 24, color: color),
              ],
            ),
            const SizedBox(height: 8),
            Text(
              value,
              style: const TextStyle(fontSize: 24, fontWeight: FontWeight.bold),
            ),
            if (change != null) ...[
              const SizedBox(height: 4),
              Row(
                children: [
                  Icon(
                    change!.startsWith('+') ? Icons.trending_up : Icons.trending_down,
                    size: 16,
                    color: change!.startsWith('+') ? Colors.green : Colors.red,
                  ),
                  const SizedBox(width: 4),
                  Text(
                    change!,
                    style: TextStyle(
                      fontSize: 12,
                      color: change!.startsWith('+') ? Colors.green : Colors.red,
                    ),
                  ),
                ],
              ),
            ],
          ],
        ),
      ),
    );
  }
}