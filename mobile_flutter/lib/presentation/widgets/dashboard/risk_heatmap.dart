import 'package:flutter/material.dart';

class RiskHeatmap extends StatelessWidget {
  final List<List<int>> data;

  const RiskHeatmap({super.key, required this.data});

  @override
  Widget build(BuildContext context) {
    return SizedBox(
      height: 200,
      child: GridView.builder(
        physics: const NeverScrollableScrollPhysics(),
        gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
          crossAxisCount: 5,
          childAspectRatio: 1,
        ),
        itemCount: 25,
        itemBuilder: (context, index) {
          final row = index ~/ 5;
          final col = index % 5;
          final value = data[row][col];
          return Container(
            margin: const EdgeInsets.all(2),
            decoration: BoxDecoration(
              color: _getColorForValue(value),
              borderRadius: BorderRadius.circular(4),
            ),
            child: Center(
              child: Text(
                value.toString(),
                style: const TextStyle(color: Colors.white, fontSize: 12),
              ),
            ),
          );
        },
      ),
    );
  }

  Color _getColorForValue(int value) {
    if (value >= 20) return Colors.red;
    if (value >= 12) return Colors.orange;
    if (value >= 8) return Colors.yellow;
    if (value >= 4) return Colors.lightGreen;
    return Colors.green;
  }
}