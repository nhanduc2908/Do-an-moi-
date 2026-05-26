import 'package:flutter/material.dart';

class RiskMatrixScreen extends StatefulWidget {
  const RiskMatrixScreen({super.key});

  @override
  State<RiskMatrixScreen> createState() => _RiskMatrixScreenState();
}

class _RiskMatrixScreenState extends State<RiskMatrixScreen> {
  final List<List<int>> _matrixData = [
    [2, 3, 5, 8, 12],
    [3, 5, 8, 12, 18],
    [5, 8, 12, 18, 25],
    [8, 12, 18, 25, 30],
    [12, 18, 25, 30, 35],
  ];

  Color _getCellColor(int value) {
    if (value >= 20) return Colors.red.shade700;
    if (value >= 12) return Colors.orange.shade600;
    if (value >= 8) return Colors.yellow.shade700;
    if (value >= 4) return Colors.lightGreen.shade400;
    return Colors.green.shade300;
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Risk Matrix'),
      ),
      body: Column(
        children: [
          Container(
            padding: const EdgeInsets.all(16),
            color: Colors.blue.shade50,
            child: const Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Column(
                  children: [
                    Text('Low Risk', style: TextStyle(color: Colors.green, fontWeight: FontWeight.bold)),
                    SizedBox(height: 4),
                    Icon(Icons.check_circle, color: Colors.green, size: 20),
                  ],
                ),
                Column(
                  children: [
                    Text('Medium Risk', style: TextStyle(color: Colors.orange, fontWeight: FontWeight.bold)),
                    SizedBox(height: 4),
                    Icon(Icons.warning, color: Colors.orange, size: 20),
                  ],
                ),
                Column(
                  children: [
                    Text('High Risk', style: TextStyle(color: Colors.red, fontWeight: FontWeight.bold)),
                    SizedBox(height: 4),
                    Icon(Icons.error, color: Colors.red, size: 20),
                  ],
                ),
              ],
            ),
          ),
          Expanded(
            child: SingleChildScrollView(
              scrollDirection: Axis.horizontal,
              child: Padding(
                padding: const EdgeInsets.all(16),
                child: Column(
                  children: [
                    Row(
                      children: [
                        const SizedBox(width: 80),
                        for (int i = 0; i < 5; i++)
                          SizedBox(
                            width: 70,
                            child: Text(
                              'Impact ${i + 1}',
                              textAlign: TextAlign.center,
                              style: const TextStyle(fontWeight: FontWeight.bold),
                            ),
                          ),
                      ],
                    ),
                    for (int i = 0; i < 5; i++)
                      Row(
                        children: [
                          SizedBox(
                            width: 80,
                            child: Text(
                              'Likelihood ${5 - i}',
                              textAlign: TextAlign.center,
                              style: const TextStyle(fontWeight: FontWeight.bold),
                            ),
                          ),
                          for (int j = 0; j < 5; j++)
                            Container(
                              width: 70,
                              height: 70,
                              decoration: BoxDecoration(
                                color: _getCellColor(_matrixData[4 - i][j]),
                                border: Border.all(color: Colors.white, width: 1),
                              ),
                              child: Center(
                                child: Column(
                                  mainAxisAlignment: MainAxisAlignment.center,
                                  children: [
                                    Text(
                                      _matrixData[4 - i][j].toString(),
                                      style: const TextStyle(color: Colors.white, fontWeight: FontWeight.bold, fontSize: 16),
                                    ),
                                    if (_matrixData[4 - i][j] >= 20)
                                      const Icon(Icons.error, color: Colors.white, size: 16),
                                  ],
                                ),
                              ),
                            ),
                        ],
                      ),
                  ],
                ),
              ),
            ),
          ),
          const SizedBox(height: 16),
          Padding(
            padding: const EdgeInsets.all(16),
            child: Card(
              child: Padding(
                padding: const EdgeInsets.all(16),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    const Text('Risk Matrix Legend', style: TextStyle(fontWeight: FontWeight.bold)),
                    const SizedBox(height: 8),
                    _buildLegendItem('Green Zone', 'Low Risk - Acceptable', Colors.green.shade300),
                    _buildLegendItem('Yellow Zone', 'Medium Risk - Monitor', Colors.yellow.shade700),
                    _buildLegendItem('Orange Zone', 'High Risk - Mitigate', Colors.orange.shade600),
                    _buildLegendItem('Red Zone', 'Critical Risk - Immediate Action', Colors.red.shade700),
                  ],
                ),
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildLegendItem(String colorName, String description, Color color) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 4),
      child: Row(
        children: [
          Container(width: 20, height: 20, color: color),
          const SizedBox(width: 12),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(colorName, style: const TextStyle(fontWeight: FontWeight.bold)),
                Text(description, style: const TextStyle(fontSize: 12, color: Colors.grey)),
              ],
            ),
          ),
        ],
      ),
    );
  }
}